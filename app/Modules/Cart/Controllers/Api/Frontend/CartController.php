<?php

namespace App\Modules\Cart\Controllers\Api\Frontend;

use App\Exceptions\Custom\ProductNotEligibleToOrderException;
use App\Http\Controllers\Controller;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductStockHelper;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductPackagingUnitDisableList;
use App\Modules\AlpasalWarehouse\Services\WarehouseProductService;
use App\Modules\AlpasalWarehouse\Services\WarehouseProductStockService;
use App\Modules\Cart\Helpers\CartHelper;
use App\Modules\Cart\Models\Cart;
use App\Modules\Cart\Requests\CartMassDeleteRequest;
use App\Modules\Cart\Requests\CartQuantityUpdateRequest;
use App\Modules\Cart\Requests\CartRequest;
use App\Modules\Cart\Resources\CartCollection;
use App\Modules\Cart\Resources\CartResource;
use App\Modules\Cart\Resources\MinimalCartResource;
use App\Modules\Cart\Services\CartService;
use App\Modules\Product\Helpers\ProductPriceHelper;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Product\Services\ProductPriceService;
use App\Modules\Product\Utilities\ProductPackagingFormatter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private $cartService,$warehouseProductService,$warehouseProductStockService;
    public function __construct(
        CartService $cartService,
        WarehouseProductService $warehouseProductService,
        WarehouseProductStockService $warehouseProductStockService
    )
    {
        $this->cartService = $cartService;
        $this->warehouseProductService = $warehouseProductService;
        $this->warehouseProductStockService = $warehouseProductStockService;
    }

    // public function index(ProductPriceService $productPriceService)
    // {
    //     $carts = $this->cartService->getAllCarts();

    //     return new CartCollection($carts,$productPriceService);
    //    // return sendSuccessResponse('Data Found!', $carts);
    // }

    public function index()
    {
        $carts = $this->cartService->getAllCarts();
        return new CartCollection($carts);
       // return sendSuccessResponse('Data Found!', $carts);
    }

    public function store(CartRequest $cartRequest)
    {

        DB::beginTransaction();
        try{
            $validatedCart = $cartRequest->validated();
            $cart = $this->cartService->storeCart($validatedCart);

            $singleProductResource['product_packaging_types'] = [];

            $warehouseProductMaster = $this->warehouseProductService->findOrFailProductByWarehouseCode(
                $cart->warehouse_code,
                $cart->product_code,
                $cart->product_variant_code
            );

            $productPrice = (new ProductPriceHelper())->getProductStorePrice($cart->warehouse_code, $cart->product_code, $cart->product_variant_code);

            $warehouseProductStock = $this->warehouseProductStockService
                ->findCurrentProductStockInWarehouse(
                    $warehouseProductMaster->warehouse_product_master_code
                );

            $integerStock = (int)$warehouseProductStock->current_stock;
            $totalCartMicroQuantity=CartHelper::getTotalOrderedMicroQuantityOfProduct(
                $cart->warehouse_code,$cart->user_code,$cart->product_code,$cart->product_variant_code);
            $integerStock = $integerStock-(int)$totalCartMicroQuantity;
            $stock = $integerStock;

            //for disabled unit list
            $disabledUnitList = WarehouseProductPackagingUnitDisableList::where('warehouse_product_master_code'
                , $warehouseProductMaster->warehouse_product_master_code)
                ->pluck('unit_name')->toArray();
            $productUnitPackagingDetail = ProductUnitPackagingHelper::findProductPackagingDetail(
                $cart->product_code, $cart->product_variant_code);
            if ($productUnitPackagingDetail) {
                $productPackagingFormatter = new ProductPackagingFormatter();

                if ($productUnitPackagingDetail->micro_unit_code && !in_array('micro', $disabledUnitList)) {

                    $stockPerPackage=$integerStock;
                    $packageStock = intval($stockPerPackage);

                    if ($stockPerPackage >= 1){

                        $arr[1] =$productUnitPackagingDetail->micro_unit_name;

                        $calculatedStock = $productPackagingFormatter->formatPackagingCombination(
                            $integerStock,array_reverse($arr,true));
                    }
                    else{
                        $calculatedStock=0;
                    }

                    array_push($singleProductResource['product_packaging_types'], [
                        'package_code' => $productUnitPackagingDetail->micro_unit_code,
                        'package_name' => $productUnitPackagingDetail->micro_unit_name,
                        'price' =>  roundPrice($productPrice),
                        'stock' =>$packageStock,
                        'display_stock' =>$calculatedStock,
                        'stock_in_cart' => CartHelper::getQuantityAddedInCart(
                            $cart->user_code,
                            $cart->warehouse_code,
                            $cart->product_code,
                            $productUnitPackagingDetail->micro_unit_code,
                            $cart->product_variant_code
                        ),
                        'description' => ''

                    ]);
                }
                if ($productUnitPackagingDetail->unit_code && !in_array('unit', $disabledUnitList)) {
                    if ($productPrice != 'N/A') {
                        $price = roundPrice($productUnitPackagingDetail->micro_to_unit_value * $productPrice);
                    } else {
                        $price = $productPrice;
                    }


                    $stockPerPackage=$integerStock/$productUnitPackagingDetail->micro_to_unit_value;
                    $packageStock = intval($stockPerPackage);

                    if ($stockPerPackage >= 1){
                        if ($productUnitPackagingDetail->unit_code){
                            $arrKey = intval($productUnitPackagingDetail->micro_to_unit_value);
                            $arr[$arrKey] =$productUnitPackagingDetail->unit_name;
                        }

                        $calculatedStock = $productPackagingFormatter->formatPackagingCombination(
                            $integerStock,array_reverse($arr,true));
                    }
                    else{
                        $calculatedStock=0;
                    }
                    array_push($singleProductResource['product_packaging_types'], [
                        'package_code' => $productUnitPackagingDetail->unit_code,
                        'package_name' => $productUnitPackagingDetail->unit_name,
                        'price' =>  $price,
                        'stock' =>  $packageStock,
                        'display_stock' =>  $calculatedStock,
                        'stock_in_cart' => CartHelper::getQuantityAddedInCart(
                            $cart->user_code,
                            $cart->warehouse_code,
                            $cart->product_code,
                            $productUnitPackagingDetail->unit_code,
                            $cart->product_variant_code
                        ),
                        'description' => '1 ' . $productUnitPackagingDetail->unit_name . ' consists ' .
                            $productUnitPackagingDetail->micro_to_unit_value . ' ' .
                            $productUnitPackagingDetail->micro_unit_name
                    ]);
                }
                if ($productUnitPackagingDetail->macro_unit_code && !in_array('macro', $disabledUnitList)) {
                    if ($productPrice != 'N/A') {
                        $price = roundPrice($productUnitPackagingDetail->micro_to_unit_value *
                            $productUnitPackagingDetail->unit_to_macro_value * $productPrice);
                    } else {
                        $price = $productPrice;
                    }

                    $stockPerPackage=$integerStock/($productUnitPackagingDetail->micro_to_unit_value*
                            $productUnitPackagingDetail->unit_to_macro_value);
                    $packageStock = intval($stockPerPackage);
                    if ($stockPerPackage >= 1){
                        if ($productUnitPackagingDetail->macro_unit_code){
                            $arrKey = intval($productUnitPackagingDetail->micro_to_unit_value *
                                $productUnitPackagingDetail->unit_to_macro_value);
                            $arr[$arrKey] =$productUnitPackagingDetail->macro_unit_name;
                        }

                        $calculatedStock = $productPackagingFormatter->formatPackagingCombination(
                            $integerStock,array_reverse($arr,true));
                    }else{
                        $calculatedStock =0;
                    }

                    array_push($singleProductResource['product_packaging_types'], [
                        'package_code' => $productUnitPackagingDetail->macro_unit_code,
                        'package_name' => $productUnitPackagingDetail->macro_unit_name,
                        'price' =>  $price,
                        'stock' =>  $packageStock,
                        'display_stock' =>  $calculatedStock,
                        'stock_in_cart' => CartHelper::getQuantityAddedInCart(
                            $cart->user_code,
                            $cart->warehouse_code,
                            $cart->product_code,
                            $productUnitPackagingDetail->macro_unit_code,
                            $cart->product_variant_code
                        ),
                        'description' => '1 ' . $productUnitPackagingDetail->macro_unit_name . ' consists ' .
                            $productUnitPackagingDetail->micro_to_unit_value *
                            $productUnitPackagingDetail->unit_to_macro_value . ' ' .
                            $productUnitPackagingDetail->micro_unit_name
                    ]);
                }
                if ($productUnitPackagingDetail->super_unit_code && !in_array('super', $disabledUnitList)) {
                    if ($productPrice != 'N/A') {
                        $price = roundPrice($productUnitPackagingDetail->micro_to_unit_value *
                            $productUnitPackagingDetail->unit_to_macro_value *
                            $productUnitPackagingDetail->macro_to_super_value * $productPrice);
                    } else {
                        $price = $productPrice;
                    }

                    $stockPerPackage=$integerStock/($productUnitPackagingDetail->micro_to_unit_value*
                            $productUnitPackagingDetail->unit_to_macro_value*
                            $productUnitPackagingDetail->macro_to_super_value);
                    $packageStock = intval($stockPerPackage);
                    if ($stockPerPackage >= 1){
                        if ($productUnitPackagingDetail->super_unit_code){

                            $arrKey = intval($productUnitPackagingDetail->micro_to_unit_value *
                                $productUnitPackagingDetail->unit_to_macro_value *
                                $productUnitPackagingDetail->macro_to_super_value);

                            $arr[$arrKey] =$productUnitPackagingDetail->super_unit_name;
                        }

                        //$arr=array_reverse($arr,true);
                        $calculatedStock = $productPackagingFormatter->formatPackagingCombination(
                            $integerStock,array_reverse($arr,true));
                    }else{
                        $calculatedStock=0;
                    }
                    array_push($singleProductResource['product_packaging_types'], [
                        'package_code' => $productUnitPackagingDetail->super_unit_code,
                        'package_name' => $productUnitPackagingDetail->super_unit_name,
                        'price' =>$price,
                        'stock' =>  $packageStock,
                        'display_stock' =>  $calculatedStock,
                        'stock_in_cart' => CartHelper::getQuantityAddedInCart(
                            $cart->user_code,
                            $cart->warehouse_code,
                            $cart->product_code,
                            $productUnitPackagingDetail->super_unit_code,
                            $cart->product_variant_code
                        ),
                        'description' => '1 ' . $productUnitPackagingDetail->super_unit_name . ' consists ' .
                            $productUnitPackagingDetail->micro_to_unit_value *
                            $productUnitPackagingDetail->unit_to_macro_value *
                            $productUnitPackagingDetail->macro_to_super_value . ' ' .
                            $productUnitPackagingDetail->micro_unit_name
                    ]);
                }

            }
            $cart->product_packaging_types= $singleProductResource['product_packaging_types'];
            $cart->overall_stock= $stock;
            $cart = new MinimalCartResource($cart);
            DB::commit();
            return sendSuccessResponse('Product Added to Cart Successfully',$cart);
        }catch(Exception $exception){
            DB::rollBack();
            if ($exception instanceof ProductNotEligibleToOrderException) {
                return sendErrorResponse($exception->getMessage(), 403, $exception->getData());
            }
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function updateQuantity(CartQuantityUpdateRequest $request,$cartCode){

        try{
            $validated = $request->validated();
            $cart = $this->cartService->updateCartQuantity($cartCode,$validated);

           $cartsStockDetail = [];
           if ($cart){
               $cartsStockDetail= $this->getCartsProductStockDetail($cart);
           }

           // $cart = new CartResource($cart);
            return sendSuccessResponse('Quantity updated successfully',$cartsStockDetail);
        }catch (Exception $exception){
            if ($exception instanceof ProductNotEligibleToOrderException) {
                return sendErrorResponse($exception->getMessage(), 403, $exception->getData());
            }
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }


    public function testupdateQuantity(CartQuantityUpdateRequest $request,$cartCode){

        try{
            $validated = $request->validated();
            $cart = $this->cartService->updateCartQuantity($cartCode,$validated);

            /*  $totalOrderedProductMicroQuantity = (int)CartHelper::getTotalOrderedMicroQuantityOfProduct(
                  $cart->warehouse_code,getAuthUserCode(),$cart->product_code,$cart->product_variant_code);*/
            $carts = Cart::where('warehouse_code',$cart->warehouse_code)
                ->where('product_code',$cart->product_code)
                ->where('product_variant_code',$cart->product_variant_code)
                ->where('user_code',$cart->user_code)->get();
            $packageQuantityFromCart =  $carts->pluck('quantity','package_code')->toArray();


            $productMicroStock = WarehouseProductStockHelper::getTotalStockOfWarehouseProductByProductCode(
                $cart->warehouse_code,$cart->product_code,$cart->product_variant_code);

            $cartsStockDetail=[];
            // $carts->map(function ($cart) use ($cartsStockDetail,$packageQuantityFromCart,$productMicroStock){
            foreach ($carts as $cart){

                $with=['microPackageType','unitPackageType','macroPackageType','superPackageType'];
                $productUnitPackagingDetail = ProductUnitPackageDetail::with($with)
                    ->where('product_code',$cart->product_code)
                    ->where('product_variant_code',$cart->product_variant_code)
                    ->where(function ($query) use ($cart){
                        $query->where('micro_unit_code',$cart->package_code)
                            ->orWhere('unit_code', $cart->package_code)
                            ->orWhere('macro_unit_code', $cart->package_code)
                            ->orWhere('super_unit_code', $cart->package_code);
                    })->first();
                if (!$productUnitPackagingDetail){
                    throw new Exception('Packaging detail not found for the product.');
                }

                $totalCartMicroQuantity=0;
                foreach ($packageQuantityFromCart as $packageCode=>$quantity){
                    $totalCartMicroQuantity += ProductUnitPackagingHelper::convertToMicroUnitQuantity(
                        $packageCode,$productUnitPackagingDetail,$quantity);
                }
                $totalMicroStock =(int)$productMicroStock - $totalCartMicroQuantity;

                $packageDisplayStock =$totalMicroStock;
                $packageStock =$totalMicroStock;
                $productPackagingFormatter = new ProductPackagingFormatter();

                if ($productUnitPackagingDetail->micro_unit_code == $cart->package_code) {

                    $stockPerPackage=$totalMicroStock;
                    $packageStock = intval($stockPerPackage);

                    $arr[1] =$productUnitPackagingDetail->microPackageType->package_name;

                    if ($stockPerPackage >= 1){
                        $packageDisplayStock = $productPackagingFormatter->formatPackagingCombination(
                            $totalMicroStock,array_reverse($arr,true));
                    }
                    else{
                        $packageDisplayStock=0;
                    }
                }
                elseif ($productUnitPackagingDetail->unit_code == $cart->package_code) {
                    $arr[1] =$productUnitPackagingDetail->microPackageType->package_name;
                    $arrKey = intval($productUnitPackagingDetail->micro_to_unit_value);
                    $arr[$arrKey] =$productUnitPackagingDetail->unitPackageType->package_name;

                    $stockPerPackage=$totalMicroStock/$productUnitPackagingDetail->micro_to_unit_value;
                    $packageStock = intval($stockPerPackage);

                    if ($stockPerPackage >= 1){
                        $packageDisplayStock = $productPackagingFormatter->formatPackagingCombination(
                            $totalMicroStock,array_reverse($arr,true));
                    }
                    else{
                        $packageDisplayStock=0;
                    }
                }
                elseif ($productUnitPackagingDetail->macro_unit_code == $cart->package_code) {

                    $arr[1] =$productUnitPackagingDetail->microPackageType->package_name;
                    $arrKey = intval($productUnitPackagingDetail->micro_to_unit_value);
                    $arr[$arrKey] =$productUnitPackagingDetail->unitPackageType->package_name;
                    $microValue=$productUnitPackagingDetail->micro_to_unit_value *
                        $productUnitPackagingDetail->unit_to_macro_value;

                    $arrKey = intval($microValue);
                    $arr[$arrKey] =$productUnitPackagingDetail->macroPackageType->package_name;


                    $stockPerPackage=$totalMicroStock/$microValue;
                    $packageStock = intval($stockPerPackage);
                    if ($stockPerPackage >= 1){

                        $packageDisplayStock = $productPackagingFormatter->formatPackagingCombination(
                            $totalMicroStock,array_reverse($arr,true));
                    }
                    else{
                        $packageDisplayStock=0;
                    }
                }
                elseif ($productUnitPackagingDetail->super_unit_code == $cart->package_code) {
                    $microValue=$productUnitPackagingDetail->micro_to_unit_value *
                        $productUnitPackagingDetail->unit_to_macro_value *
                        $productUnitPackagingDetail->macro_to_super_value;

                    $arr[1] =$productUnitPackagingDetail->microPackageType->package_name;
                    $arrKey = intval($productUnitPackagingDetail->micro_to_unit_value);
                    $arr[$arrKey] =$productUnitPackagingDetail->unitPackageType->package_name;
                    $arrKey = intval($productUnitPackagingDetail->micro_to_unit_value *
                        $productUnitPackagingDetail->unit_to_macro_value);
                    $arr[$arrKey] =$productUnitPackagingDetail->macroPackageType->package_name;

                    $arrKey = intval($microValue);
                    $arr[$arrKey] =$productUnitPackagingDetail->superPackageType->package_name;

                    $stockPerPackage=$totalMicroStock/$microValue;
                    $packageStock = intval($stockPerPackage);
                    if ($stockPerPackage >= 1){

                        $packageDisplayStock = $productPackagingFormatter->formatPackagingCombination(
                            $totalMicroStock,array_reverse($arr,true));
                    }
                    else{
                        $packageDisplayStock=0;
                    }
                }

                $cartsStockDetail[$cart->cart_code] =[
                    'micro_stock' =>$totalMicroStock,
                    'stock' =>$packageStock,
                    'display_stock' =>$packageDisplayStock,
                ];
            };

            // $cart = new CartResource($cart);
            return sendSuccessResponse('Quantity updated successfully',$cartsStockDetail);
        }catch (Exception $exception){
            if ($exception instanceof ProductNotEligibleToOrderException) {
                return sendErrorResponse($exception->getMessage(), 403, $exception->getData());
            }
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function destroy($cartCode)
    {
        try{
            $cart=$this->cartService->deleteCart($cartCode);
            $cartsStockDetail=[];

            if ($cart){
                $cartsStockDetail = $this->getCartsProductStockDetail($cart);
            }
            return sendSuccessResponse('Product Removed from Cart Successfully',$cartsStockDetail);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    private function getCartsProductStockDetail($cart){
        $carts = Cart::where('warehouse_code',$cart->warehouse_code)
            ->where('product_code',$cart->product_code)
            ->where('product_variant_code',$cart->product_variant_code)
            ->where('user_code',$cart->user_code)->get();
        //dd($carts);
        $cartsStockDetail=[];
        if (!$carts){
            return $cartsStockDetail;
        }
        $packageQuantityFromCart =  $carts->pluck('quantity','package_code')->toArray();

        $productMicroStock = WarehouseProductStockHelper::getTotalStockOfWarehouseProductByProductCode(
            $cart->warehouse_code,$cart->product_code,$cart->product_variant_code);

        $with=['microPackageType','unitPackageType','macroPackageType','superPackageType'];
        $productUnitPackagingDetail = ProductUnitPackageDetail::with($with)
            ->where('product_code',$cart->product_code)
            ->where('product_variant_code',$cart->product_variant_code)
            ->first();

        if (!$productUnitPackagingDetail){
            throw new Exception('Packaging detail not found for the product.');
        }

        $productUnitPackagingCodes=[
            'micro_unit_code' => $productUnitPackagingDetail->micro_unit_code,
            'unit_code' => $productUnitPackagingDetail->unit_code,
            'macro_unit_code' => $productUnitPackagingDetail->macro_unit_code,
            'super_unit_code' => $productUnitPackagingDetail->super_unit_code,
        ];
        $productUnitPackagingCodes = array_filter($productUnitPackagingCodes);

        $totalCartMicroQuantity=0;
        foreach ($packageQuantityFromCart as $packageCode=>$quantity){
            $totalCartMicroQuantity += ProductUnitPackagingHelper::convertToMicroUnitQuantity(
                $packageCode,$productUnitPackagingDetail,$quantity);
        }


        $totalMicroStock =(int)$productMicroStock - $totalCartMicroQuantity;
        foreach ($carts as $i => $cart){
            $arr=[];
            if (!in_array($cart->package_code,$productUnitPackagingCodes)){
                throw new Exception('Packaging detail not found for the product.');
            }

            $packageDisplayStock =$totalMicroStock;
            $packageStock =$totalMicroStock;
            $productPackagingFormatter = new ProductPackagingFormatter();

            if ($productUnitPackagingDetail->micro_unit_code == $cart->package_code) {

                $stockPerPackage=$totalMicroStock;
                $packageStock = intval($stockPerPackage);

                $arr[1] =$productUnitPackagingDetail->microPackageType->package_name;

                if ($stockPerPackage >= 1){
                    $packageDisplayStock = $productPackagingFormatter->formatPackagingCombination(
                        $totalMicroStock,array_reverse($arr,true));
                }
                else{
                    $packageDisplayStock=0;
                }
            }
            elseif ($productUnitPackagingDetail->unit_code == $cart->package_code) {
                $arr[1] =$productUnitPackagingDetail->microPackageType->package_name;
                $arrKey = intval($productUnitPackagingDetail->micro_to_unit_value);
                $arr[$arrKey] =$productUnitPackagingDetail->unitPackageType->package_name;

                $stockPerPackage=$totalMicroStock/$productUnitPackagingDetail->micro_to_unit_value;
                $packageStock = intval($stockPerPackage);

                if ($stockPerPackage >= 1){
                    $packageDisplayStock = $productPackagingFormatter->formatPackagingCombination(
                        $totalMicroStock,array_reverse($arr,true));
                }
                else{
                    $packageDisplayStock=0;
                }
            }
            elseif ($productUnitPackagingDetail->macro_unit_code == $cart->package_code) {

                $arr[1] =$productUnitPackagingDetail->microPackageType->package_name;
                $arrKey = intval($productUnitPackagingDetail->micro_to_unit_value);
                $arr[$arrKey] =$productUnitPackagingDetail->unitPackageType->package_name;
                $microValue=$productUnitPackagingDetail->micro_to_unit_value *
                    $productUnitPackagingDetail->unit_to_macro_value;

                $arrKey = intval($microValue);
                $arr[$arrKey] =$productUnitPackagingDetail->macroPackageType->package_name;


                $stockPerPackage=$totalMicroStock/$microValue;
                $packageStock = intval($stockPerPackage);
                if ($stockPerPackage >= 1){

                    $packageDisplayStock = $productPackagingFormatter->formatPackagingCombination(
                        $totalMicroStock,array_reverse($arr,true));
                }
                else{
                    $packageDisplayStock=0;
                }
            }
            elseif ($productUnitPackagingDetail->super_unit_code == $cart->package_code) {
                $microValue=$productUnitPackagingDetail->micro_to_unit_value *
                    $productUnitPackagingDetail->unit_to_macro_value *
                    $productUnitPackagingDetail->macro_to_super_value;

                $arr[1] =$productUnitPackagingDetail->microPackageType->package_name;
                $arrKey = intval($productUnitPackagingDetail->micro_to_unit_value);
                $arr[$arrKey] =$productUnitPackagingDetail->unitPackageType->package_name;
                $arrKey = intval($productUnitPackagingDetail->micro_to_unit_value *
                    $productUnitPackagingDetail->unit_to_macro_value);
                $arr[$arrKey] =$productUnitPackagingDetail->macroPackageType->package_name;

                $arrKey = intval($microValue);
                $arr[$arrKey] =$productUnitPackagingDetail->superPackageType->package_name;

                $stockPerPackage=$totalMicroStock/$microValue;
                $packageStock = intval($stockPerPackage);
                if ($stockPerPackage >= 1){

                    $packageDisplayStock = $productPackagingFormatter->formatPackagingCombination(
                        $totalMicroStock,array_reverse($arr,true));
                }
                else{
                    $packageDisplayStock=0;
                }
            }

            $cartsStockDetail[$i] =[
                'cart_code'=>$cart->cart_code,
                'micro_stock' =>$totalMicroStock,
                'stock' =>$packageStock,
                'display_stock' =>$packageDisplayStock,
                'total_stock' =>$packageStock + $cart->quantity,
            ];
        };

        return $cartsStockDetail;
    }

    public function massDestroy(CartMassDeleteRequest $request){

        try{
            $validated = $request->validated();
            $differentProductCartsCollection=$this->cartService->massDeleteCartOfUser(
                $validated,getAuthUserCode());
            $cartsStockDetail=[];
            foreach ($differentProductCartsCollection as $key=>$cart){
                $cartsStockDetail[$key] = $this->getCartsProductStockDetail($cart);
            }
            $cartsStockDetail=array_merge(...array_values($cartsStockDetail));

            return sendSuccessResponse('Product Removed from Cart Successfully',$cartsStockDetail);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
    public function  getUserCartCounts(){

        try {
            $carts_count = $this->cartService->getUserCartCounts();
            return sendSuccessResponse('Total No of Carts',['total_carts'=>$carts_count]);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }



}
