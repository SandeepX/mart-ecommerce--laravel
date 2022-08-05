<?php

namespace App\Modules\Cart\Services;

use App\Exceptions\Custom\ProductNotEligibleToOrderException;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductHelper;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductPackagingUnitDisableList;
use App\Modules\Cart\Helpers\CartHelper;
use App\Modules\Cart\Repositories\CartRepository;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Store\Helpers\StoreOrderHelper;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use App\Modules\Store\Services\StoreService;
use App\Modules\Vendor\Repositories\VendorProductPackagingHistoryRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class CartService
{
    private $cartRepository;
    private $productRepository,$vendorProductPackagingHistoryRepository,$storeService;

    public function __construct(
        CartRepository $cartRepository,
        ProductRepository $productRepository,
        VendorProductPackagingHistoryRepository $vendorProductPackagingHistoryRepository,
        StoreService $storeService
    )
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->vendorProductPackagingHistoryRepository = $vendorProductPackagingHistoryRepository;
        $this->storeService = $storeService;
    }

    public function findCartByCode($cartCode)
    {
        $this->cartRepository->findCartByCode($cartCode);
    }

    public function getAllCarts()
    {
        $bindings =[
            'store_user_code' => getAuthUserCode()
        ];

        //dd($bindings);
        $rawQuery = "
              SELECT t1.*,(SELECT product_packaging_unit_rate_function_by_product_code(t1.package_code,t1.store_price,t1.product_code,t1.product_variant_code)) as ordered_package_price
              from
                   (SELECT product_name,
            productImages.image,
            products_master.slug,
            product_variants.product_variant_name,
            carts.quantity,
            cart_code,
            carts.product_variant_code,
            carts.product_code,
            products_master.is_taxable,
            carts.user_code,
            wpm.is_active,
            wpm.warehouse_product_master_code,
            carts.warehouse_code,
            wpm.current_stock,
            package_types.package_name,
            package_types.package_code,
            (select mrp-(Case wholesale_margin_type when 'p' THEN ((wholesale_margin_value/100)*mrp) else wholesale_margin_value END)-(Case retail_margin_type when 'p' THEN ((retail_margin_value/100)*mrp) else retail_margin_value END) )as store_price
            FROM `carts` inner join
            warehouse_product_master as wpm on
            wpm.warehouse_code=carts.warehouse_code and
            wpm.product_code=carts.product_code and
            (carts.product_variant_code=wpm.product_variant_code or carts.product_variant_code is null and wpm.product_variant_code is null)
            inner join
            products_master on wpm.product_code=products_master.product_code
            left join
            product_variants on wpm.product_variant_code=product_variants.product_variant_code
            inner join
             warehouse_product_price_master on wpm.warehouse_product_master_code=warehouse_product_price_master.warehouse_product_master_code
            inner join
            (
                SELECT product_code,image from product_images where id in (SELECT min(id) from product_images group by product_code)
            )
            as  productImages on wpm.product_code=productImages.product_code
            LEFT JOIN package_types
            on package_types.package_code = carts.package_code
            where carts.user_code=:store_user_code
              and carts.deleted_at is null order by carts.updated_at DESC ) as t1
     ";

        $results = DB::select($rawQuery,$bindings);
        return $results;
    }

    public function storeCart($validatedCart)
    {
        try{
            $product_slug = $validatedCart['product_slug'];

            $product_variant_code = null;
            $store = $this->storeService->findStoreByCode(getAuthStoreCode());
            if($store->has_purchase_power == 0)
            {
                throw new Exception('The store has no purchasing power');
            }
            $product = $this->productRepository->findOrFailProductBySlug($product_slug);
            $validatedCart['product_code'] = $product->product_code;

            $product_variant_name= null;
            $product_variant_code=null;

            if ($product->hasVariants()) {

                $product_variant_code = $validatedCart['combination_name'];
                $product_variant = $product->productVariants()
                    ->where('product_variant_code', $product_variant_code)
                    ->first();

                if (!$product_variant) {
                    throw new Exception('No Such Variant Found !');
                }

                $product_variant_name = $product_variant->product_variant_name;
                $product_variant_code = $product_variant->product_variant_code;
            }

          /*  if (!ProductUnitPackagingHelper::isProductPackagedByPackageCode(
                $validatedCart['package_code'],$product->product_code,$product_variant_code)){
                throw new Exception('Add to cart failed: package type does not exist for product.');
            }*/

            $productPackagingHistory = $this->vendorProductPackagingHistoryRepository->getLatestProductPackagingHistoryByPackageCode(
                $validatedCart['package_code'],$product->product_code,$product_variant_code);

            if (!$productPackagingHistory){
                throw new Exception('Add to cart failed: package type does not exist for '.
                    $product->product_name. $product_variant_name);
            }


            //orderedMicroQuantity
            $convertedOrderedMicroQuantity= ProductUnitPackagingHelper::convertToMicroUnitQuantity(
                $validatedCart['package_code'], $productPackagingHistory,$validatedCart['quantity'] );

            $validatedCart['product_variant_code'] = $product_variant_code;

            $validatedCart['warehouse_code'] = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore(getAuthStoreCode());


            $existingCartItem = $this->cartRepository->getCart(
                $product->product_code,
                $product_variant_code,
                getAuthUserCode(),
                $validatedCart['warehouse_code'],
                $validatedCart['package_code']
            );

            if($existingCartItem){
                $validatedCart['quantity']= (int) $existingCartItem->quantity + (int) $validatedCart['quantity'];
            }
            $existingCartTotalMicroQuantity =CartHelper::getTotalOrderedMicroQuantityOfProduct(
                $validatedCart['warehouse_code'], getAuthUserCode(),$product->product_code,$product_variant_code);
            if ($existingCartTotalMicroQuantity){
                $convertedOrderedMicroQuantity = $convertedOrderedMicroQuantity+ $existingCartTotalMicroQuantity;
            }

            $validatedData=[
                'product_code' =>$validatedCart['product_code'],
                'product_variant_code' =>$validatedCart['product_variant_code'],
                'package_code' =>$validatedCart['package_code'],
                'product_packaging_detail' =>$productPackagingHistory,
            ];
            $isProductEligibleData = StoreOrderHelper::newisProductEligibleToOrderByStore($validatedCart['warehouse_code'],
                $convertedOrderedMicroQuantity,$validatedData);

            if(!$isProductEligibleData['isEligible']){
                throw new ProductNotEligibleToOrderException($isProductEligibleData['message'],$isProductEligibleData);
            }

            $validatedCart['user_code'] = getAuthUserCode();
            DB::beginTransaction();
            $cartItem = $this->cartRepository->storeCart($validatedCart,$existingCartItem);
            DB::commit();
            return $cartItem;

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }


    }

    public function updateCartQuantity($cartCode,$validatedData)
    {
        try {
            $cart = $this->cartRepository->findOrFailCartByUserCode($cartCode, getAuthUserCode());
            $cartQty = $cart->quantity;
            $qtyToCheck = $validatedData['quantity'] - $cartQty;

            // $cartQty = package Code => eg .9 tins
            // incoming decremented = eg. 8
            // $qtyToChange = INCOMING - EXISTING cart PKG QTY = 8-9 = -1
            //  OVERALL (p+pV) MICR0 QTY + (- 1 MICROQTY) =

            $productPackagingHistory = $this->vendorProductPackagingHistoryRepository->getLatestProductPackagingHistoryByPackageCode(
                $cart['package_code'],$cart['product_code'], $cart['product_variant_code']);

            if (!$productPackagingHistory){
                throw new Exception('Update quantity failed: package type does not exist,');
            }

            //orderedMicroQuantity

            $convertedOrderedMicroQuantity= ProductUnitPackagingHelper::convertToMicroUnitQuantity(
                $cart['package_code'], $productPackagingHistory,$qtyToCheck);

            $existingCartTotalMicroQuantity =CartHelper::getTotalOrderedMicroQuantityOfProduct(
                $cart['warehouse_code'], getAuthUserCode(),$cart['product_code'], $cart['product_variant_code']);
            if ($existingCartTotalMicroQuantity){
                $convertedOrderedMicroQuantity = $convertedOrderedMicroQuantity + $existingCartTotalMicroQuantity;
            }

            $cartProductDetails=[
                'product_code' =>$cart['product_code'],
                'product_variant_code' =>$cart['product_variant_code'],
                'package_code' =>$cart['package_code'],
                'product_packaging_detail' =>$productPackagingHistory,
            ];

             $validatedData = $validatedData + $cartProductDetails;

            $isProductEligibleData = StoreOrderHelper::newisProductEligibleToOrderByStore($cart['warehouse_code'],
                $convertedOrderedMicroQuantity,$validatedData);

            if(!$isProductEligibleData['isEligible']){
                throw new ProductNotEligibleToOrderException($isProductEligibleData['message'],$isProductEligibleData);
            }

            DB::beginTransaction();

            $cart = $this->cartRepository->updateQuantity($cart, $validatedData['quantity']);

            DB::commit();

            return $cart;
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }

    public function deleteCart($cartCode)
    {
        try{
            DB::beginTransaction();
            $this->checkUserCart($cartCode);
            //$cart = $this->cartRepository->findOrFailByCode($cartCode);
            $cart = $this->cartRepository->findOrFailCartByUserCode($cartCode,getAuthUserCode());
            $cartClone = $cart->replicate();
            $this->cartRepository->deleteCart($cart);
            DB::commit();
            //dd($cartClone);
            return $cartClone;
        }catch (Exception $exception){
            DB::rollBack();
        }
    }

    public function massDeleteCartOfUser($validated, $userCode)
    {
        try {
            DB::beginTransaction();
            $cartsArr=[];
            foreach ($validated['cart_codes']  as $cartCode){
                array_push($cartsArr,$this->cartRepository->findOrFailCartByUserCode($cartCode,$userCode));
            }

            $differentProductCartsCollection = collect($cartsArr)->unique(function ($item)
                {
                    return $item['warehouse_code'] .$item['product_code'] . $item['product_variant_code']. $item['user_code'];
                });
            //dd($differentProductCartsCollection);
            $this->cartRepository->massDeleteCarts($validated['cart_codes'], $userCode);
            DB::commit();
            return $differentProductCartsCollection;
        } catch (Exception $exception) {

            DB::rollBack();
            throw  $exception;
        }

    }

    public function deleteCartByUser()
    {
        $this->cartRepository->deleteCartByUser(auth()->user());
    }

    public function checkUserCart($cartCode)
    {
        $cartCodes = auth()->user()->carts()->pluck('cart_code')->toArray();
        if (!in_array($cartCode, $cartCodes)) {
            throw new Exception('Cart Does Not Belongs To You !!', 400);
        }
    }

    public function  getUserCartCounts(){
       return $carts_count = $this->cartRepository->getAllCarts(auth()->user())->count('id');
    }
}
