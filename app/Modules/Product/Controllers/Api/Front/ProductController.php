<?php

namespace App\Modules\Product\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductHelper;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductStockHelper;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductPackagingUnitDisableList;
use App\Modules\AlpasalWarehouse\Services\WarehouseProductService;
use App\Modules\AlpasalWarehouse\Services\WarehouseProductStockService;
use App\Modules\Cart\Helpers\CartHelper;
use App\Modules\Cart\Models\Cart;
use App\Modules\Product\Helpers\ProductHelper;
use App\Modules\Product\Helpers\ProductPriceHelper;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Resources\AllWarehouseProductsListingCollection;
use App\Modules\Product\Resources\ProductVariantPriceImageResource;
use App\Modules\Product\Resources\SingleProductListingCollection;
use App\Modules\Product\Resources\SingleProductResourceForFrontend;
use App\Modules\Product\Services\ProductImageService;

//use App\Modules\Product\Services\ProductPriceService;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\ProductVariantGroup\PVGroupBulkImageService;
use App\Modules\Product\Services\ProductVariantService;
use App\Modules\Product\Utilities\ProductPackagingFormatter;
use App\Modules\ProductRatingReview\Helpers\ProductRatingReviewHelper;
use App\Modules\Store\Helpers\NormalOrderVariantSelectionHelper;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use App\Modules\Store\Services\StoreService;
use Exception;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    private $productService, $productVariantService, $productPriceService, $productVariantImageService;

    private $warehouseProductService,$warehouseProductStockService,$storeService;

    private $pvGroupBulkImageService;

    public function __construct(ProductService $productService,
                                ProductVariantService $productVariantService,
        // ProductPriceService $productPriceService,
                                ProductImageService $productImageService,
                                WarehouseProductService $warehouseProductService,
                                WarehouseProductStockService $warehouseProductStockService,
                                StoreService $storeService,
                                PVGroupBulkImageService $pvGroupBulkImageService
    ){
        $this->productService = $productService;
        $this->productVariantService = $productVariantService;
        //$this->productPriceService = $productPriceService;
        $this->productVariantImageService = $productImageService;
        $this->warehouseProductService = $warehouseProductService;
        $this->warehouseProductStockService = $warehouseProductStockService;
        $this->storeService = $storeService;
        $this->pvGroupBulkImageService = $pvGroupBulkImageService;
    }

    public function show($productSlug)
    {
        try {

            $productUnitPackagingDetail = null;
            $disabledUnitList = [];
            $userCode = null;
            $warehouseCode = null;
            if ((Auth::guard('api')->check()) && Auth::guard('api')->user()->isStoreUser()) {
                $storeCode = getAuthGuardStoreCode();
                $userCode = getAuthGuardUserCode();
                $warehouseCode = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore($storeCode);

                $with = [
                    'productVariants',
                    'warehouseProducts' => function ($query) use ($warehouseCode) {
                        $query->where('warehouse_code', $warehouseCode);
                    }
                    /*'unitPackagingDetails',
                    'unitPackagingDetails.microPackageType',
                    'unitPackagingDetails.unitPackageType',
                    'unitPackagingDetails.macroPackageType',
                    'unitPackagingDetails.superPackageType',
                    'unitPackagingDetails.productVariant'*/
                ];
                //$product = $this->warehouseProductService->findOrFailQualifiedProductBySlugWith($warehouseCode,$productSlug,$with);

                $product = WarehouseProductHelper::findOrFailQualifiedWarehouseProductBySlug(
                    $warehouseCode,
                    $productSlug,
                    $with,
                    [
                        'product_code', 'product_name', 'slug', 'description', 'vendor_code', 'brand_code',
                        'category_code', 'sensitivity_code', 'remarks', 'is_taxable', 'is_active', 'video_link',
                        'highlights'
                    ]
                );
               // dd($product);
                if (!$product->hasVariants()) {
                    $productUnitPackagingDetail = ProductUnitPackagingHelper::findProductPackagingDetail($product->product_code);
                    //for disabled unit list
                    $disabledUnitList = WarehouseProductPackagingUnitDisableList::where('warehouse_product_master_code'
                        , $product->warehouseProducts[0]->warehouse_product_master_code)
                        ->pluck('unit_name')->toArray();

                }
                //dd($product);
                $totalCartMicroQuantity=CartHelper::getTotalOrderedMicroQuantityOfProductByProductCode($warehouseCode,$userCode,$product->product_code);
                $warehouseProductStock = WarehouseProductStockHelper::getTotalStockOfWarehouseProduct($product->product_code, $warehouseCode);
                $warehouseProductStock = (int)$warehouseProductStock-(int)$totalCartMicroQuantity;
                $product['warehouse_product_stock'] = $warehouseProductStock;
                if (!$product->hasVariants()) {
                    $product['order_qty_limits'] = WarehouseProductHelper::getMinMaxQtyLimitOfWarehouseProduct($warehouseCode, $product->product_code, null);
                }
                $product['rating'] = ProductRatingReviewHelper::getProductRatingForStore($storeCode, $product->product_code, $warehouseCode);
            } else {
                $product = $this->productService->findOrFailVerifiedProductBySlugWith($productSlug,
                    [
                        'productVariants',
                        /*'unitPackagingDetails',
                        'unitPackagingDetails.microPackageType',
                        'unitPackagingDetails.unitPackageType',
                        'unitPackagingDetails.macroPackageType',
                        'unitPackagingDetails.superPackageType',
                        'unitPackagingDetails.productVariant',*/
                    ],
                    ['product_code', 'product_name', 'slug', 'description', 'vendor_code', 'brand_code', 'category_code',
                        'sensitivity_code', 'remarks', 'is_taxable', 'is_active', 'video_link', 'highlights'
                    ]
                );
                $product['warehouse_product_stock'] = 'N/A';
                $product['rating'] = ProductRatingReviewHelper::getProductRatingForGuest($product->product_code);
            }
            $product['product_packaging_detail'] = $productUnitPackagingDetail;
            $product['disabled_unit_list'] = $disabledUnitList;

           // dd($product);

            $product['first_variant'] = NormalOrderVariantSelectionHelper::getNormalOrderProductFirstVariantDetails(
                                            $product->product_code,
                                            $warehouseCode
                                        );
            $product['user_code'] = $userCode;
            $product['warehouse_code'] = $warehouseCode;

            $product = new SingleProductResourceForFrontend($product);
            return sendSuccessResponse('Data Found!', $product);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 404);
        }
    }

    public function getProductVariantImageAndPrice($productSlug, $variantCode)
    {

        try {

            $singleProductResource['product_packaging_types'] = [];
            $product = $this->productService->findOrFailProductBySlug($productSlug);
            $productVariant = $this->productVariantService->findOrFailVariantByProductCodeAndVariantCode($product->product_code, $variantCode);

            $productVariantImages = $this->productVariantImageService->getImageListOfProductCodeAndVariantCode($product->product_code, $productVariant->product_variant_code);

            $productVariantImages = $this->productVariantImageService->concatImagePath($productVariantImages);

            if(!count($productVariantImages)>0){
                $productVariantBulkImages = $this->pvGroupBulkImageService->getGroupBulkImagesByGroupCode($productVariant->product_variant_group_code);
                $productVariantImages = $this->pvGroupBulkImageService->concatImagePath($productVariantBulkImages);
            }

            $stock = 'N/A';
            $integerStock =0;

            if ((Auth::guard('api')->check()) && Auth::guard('api')->user()->isStoreUser()) {
                $userCode = getAuthGuardUserCode();

                $store = $this->storeService->findStoreByCode(getAuthGuardStoreCode());
                if(!($store->status == "approved"))
                {
                    throw new Exception('The store is not approved yet');
                }
                $warehouseCode= StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore(getAuthGuardStoreCode());

                $disabledUnitList = [];
                if ($warehouseCode) {
                    $productPrice = (new ProductPriceHelper())->getProductStorePrice($warehouseCode, $product->product_code, $productVariant->product_variant_code);

                    $warehouseProductMaster = $this->warehouseProductService->findOrFailProductByWarehouseCode(
                        $warehouseCode,
                        $product->product_code,
                        $productVariant->product_variant_code
                    );

                    $warehouseProductStock = $this->warehouseProductStockService
                        ->findCurrentProductStockInWarehouse(
                            $warehouseProductMaster->warehouse_product_master_code
                        );

                    $integerStock = (int)$warehouseProductStock->current_stock;
                    $totalCartMicroQuantity=CartHelper::getTotalOrderedMicroQuantityOfProduct(
                        $warehouseCode,$userCode,$product->product_code,$productVariant->product_variant_code);
                    $integerStock = $integerStock-(int)$totalCartMicroQuantity;
                    $stock = $integerStock;
                     $order_qty_limits = WarehouseProductHelper::getMinMaxQtyLimitOfWarehouseProduct(
                        $warehouseCode,
                        $product->product_code,
                        $productVariant->product_variant_code
                    );


                    //for disabled unit list
                    $disabledUnitList = WarehouseProductPackagingUnitDisableList::where('warehouse_product_master_code'
                        , $warehouseProductMaster->warehouse_product_master_code)
                        ->pluck('unit_name')->toArray();
                }

                $productUnitPackagingDetail = ProductUnitPackagingHelper::findProductPackagingDetail(
                    $product->product_code, $productVariant->product_variant_code);

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
                                $userCode,
                                $warehouseCode,
                                $product->product_code,
                                $productUnitPackagingDetail->micro_unit_code,
                                $productVariant->product_variant_code
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
                                $userCode,
                                $warehouseCode,
                                $product->product_code,
                                $productUnitPackagingDetail->unit_code,
                                $productVariant->product_variant_code
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
                                $userCode,
                                $warehouseCode,
                                $product->product_code,
                                $productUnitPackagingDetail->macro_unit_code,
                                $productVariant->product_variant_code
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
                                $userCode,
                                $warehouseCode,
                                $product->product_code,
                                $productUnitPackagingDetail->super_unit_code,
                                $productVariant->product_variant_code
                            ),
                            'description' => '1 ' . $productUnitPackagingDetail->super_unit_name . ' consists ' .
                                $productUnitPackagingDetail->micro_to_unit_value *
                                $productUnitPackagingDetail->unit_to_macro_value *
                                $productUnitPackagingDetail->macro_to_super_value . ' ' .
                                $productUnitPackagingDetail->micro_unit_name
                        ]);
                    }

                }
            } else {
                $productPrice = 'N/A';
                $order_qty_limits = [];
            }

            $response['data'] = [
                'rate' =>  roundPrice($productPrice),
                'images' => $productVariantImages,
                'stock' => $stock,
                'product_packaging_types' => $singleProductResource['product_packaging_types'],
                'order_qty_limits' => $order_qty_limits
            ];


            return response()->json($response, 200);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 404);
        }
    }


    public function getAssociatedNormalOrderVariantDetails(
        $productCode,$variantValueCode,$variantDepth,$ancestorCode){

        try{
            $warehouseCode = null;
            if ((Auth::guard('api')->check()) && Auth::guard('api')->user()->isStoreUser()) {
                $warehouseCode = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore(getAuthGuardStoreCode());
            }
          ///  $warehouseCodes = convertToArray($warehouseCode);
            $variantAssociatedValues = NormalOrderVariantSelectionHelper::getAssociatedNormalOrderVariantDetails(
                $productCode,$variantValueCode,$variantDepth+1,$ancestorCode,$warehouseCode
            );

            return sendSuccessResponse('Data Found',$variantAssociatedValues);

        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), 404);
        }


    }

    public function getSingleProductListViewDetails($productCode){

        try{
            $userCode = getAuthUserCode();
            $warehouseCode = null;
            $this->productService->findOrFailProductByCode($productCode);
            if ((Auth::guard('api')->check()) && Auth::guard('api')->user()->isStoreUser()) {
                $warehouseCode = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore(getAuthGuardStoreCode());
            }

            $productListDetails = ProductHelper::singleProductListViewDetailsOfWarehouse(
                $warehouseCode,
                $userCode,
                $productCode
            );

            return  new SingleProductListingCollection(collect($productListDetails));

        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), 404);
        }

    }

    public function getAllProductListViewDetails(Request $request){

        try{

            $userCode = getAuthUserCode();

            $warehouseCode = null;
            if ((Auth::guard('api')->check()) && Auth::guard('api')->user()->isStoreUser()) {
                $warehouseCode = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore(getAuthGuardStoreCode());
            }

            $productListDetails = ProductHelper::allProductsListViewDetailsOfWarehouse(
                $warehouseCode,
                $userCode
            );

           // dd($productListDetails);

            return  new AllWarehouseProductsListingCollection($productListDetails);

        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), 404);
        }
    }




}
