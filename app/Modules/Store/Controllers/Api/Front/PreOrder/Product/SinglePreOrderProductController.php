<?php

namespace App\Modules\Store\Controllers\Api\Front\PreOrder\Product;

use App\Http\Controllers\Controller;
use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderProductFilter;
use App\Modules\AlpasalWarehouse\Models\PreOrder\PreOrderPackagingUnitDisableList;
use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderProduct;
use App\Modules\AlpasalWarehouse\Resources\WarehousePreOrderProductCollection;
use App\Modules\AlpasalWarehouse\Resources\WarehousePreOrderRelatedProductResource;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderProductService;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderService;
use App\Modules\Product\Helpers\ProductPriceHelper;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Resources\PreOrder\AllWarehousePreOrderProductsListingCollection;
use App\Modules\Product\Resources\PreOrder\SinglePreOrderProductListingCollection;
use App\Modules\Product\Services\ProductImageService;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\ProductVariantGroup\PVGroupBulkImageService;
use App\Modules\Product\Services\ProductVariantService;
use App\Modules\Store\Helpers\PreOrder\PreOrderProductHelper;
use App\Modules\Store\Helpers\PreOrder\PreOrderVariantSelectionHelper;
use App\Modules\Store\Helpers\PreOrder\StorePreOrderHelper;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use App\Modules\Store\Resources\StorePreOrder\SinglePreOrderProductResourceForFrontend;
use Exception;

use Auth;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SinglePreOrderProductController extends Controller
{
    private $productService,
            $productVariantService,
            $productVariantImageService,
            $warehousePreOrderService;

    private $pvGroupBulkImageService;

    public function __construct(
        ProductService $productService,
        ProductVariantService $productVariantService,
        ProductImageService $productVariantImageService,
        WarehousePreOrderService $warehousePreOrderService,
        PVGroupBulkImageService $pvGroupBulkImageService
    )

    {
        $this->productService = $productService;
        $this->productVariantService = $productVariantService;
        $this->productVariantImageService = $productVariantImageService;
        $this->warehousePreOrderService = $warehousePreOrderService;
        $this->pvGroupBulkImageService = $pvGroupBulkImageService;
    }

    //show page for single product
    public function getPreOrderProductInfo($productSlug,$warehousePreOrderListingCode)
    {
        try{
                $with=[
                    'productVariants'
                ];

            $warehouseCode = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore(getAuthStoreCode());

            $warehousePreOrderListingRelations =  ['storePreOrders'=>function($query){
                $query->where('store_code',getAuthStoreCode());
            }];
            $warehousePreOrderListing = $this->warehousePreOrderService
                                            ->findOrFailWarehousePreOrderByWarehouseCode(
                                                $warehousePreOrderListingCode,
                                                $warehouseCode,
                                                $warehousePreOrderListingRelations
                                            );

            if($warehousePreOrderListing->isPastFinalizationTime()){
                throw new Exception('Information not available after finalization time ends',402);
            }


            $product= StorePreOrderHelper::getProductInWarehousePreOrderList(
                    $warehousePreOrderListing->warehouse_preorder_listing_code,
                    $productSlug,
                    $with
                );

            $product['rate'] = 'N/A';
            $product['can_pre_order'] = false;


            if($warehousePreOrderListing->isPreOrderable()){
                $product['can_pre_order'] = true;
            }

            $product['has_start_time_past'] = $warehousePreOrderListing->isPastStartTime();

            $product['store_pre_order_code']=  count($warehousePreOrderListing->storePreOrders)
                                                ? ($warehousePreOrderListing->storePreOrders->first())['store_preorder_code']
                                                : null;


            $product['is_taxable'] = $product->is_taxable;

            $product['product_packaging_detail'] = null;
            if(!$product->hasVariants()){
                $productUnitPackagingDetail = ProductUnitPackagingHelper::findProductPackagingDetail($product->product_code);
                $product['product_packaging_detail'] = $productUnitPackagingDetail;

                $warehousePreOrderProduct = WarehousePreOrderProduct::where('warehouse_preorder_listing_code',$warehousePreOrderListingCode)
                    ->where('product_code',$product->product_code)->where('product_variant_code',null)->firstOrFail();

                $product['disabled_unit_list'] = PreOrderPackagingUnitDisableList::where('warehouse_preorder_product_code'
                    ,$warehousePreOrderProduct->warehouse_preorder_product_code)
                    ->pluck('unit_name')->toArray();

                if($warehousePreOrderListing->isDisplayable()){
                    $productRate= (new ProductPriceHelper())
                        ->findPreOrderProductStorePrice(
                            $warehouseCode,
                            $warehousePreOrderListingCode,
                            $product->product_code
                        );
                    if ($productRate){
                        $product['rate'] = $productRate;
                    }
                }

            }
            else{
                if($warehousePreOrderListing->isDisplayable()){
                    $product['rate'] = (new ProductPriceHelper())
                        ->getPreOrderProductStorePriceRange(
                            $warehousePreOrderListingCode,
                            $product->product_code
                        );
                }

            }
            $product['first_variant'] = PreOrderVariantSelectionHelper::getPreOrderProductFirstVariantDetails(
                                                $warehousePreOrderListingCode,$product->product_code
                                            );
            $product = new SinglePreOrderProductResourceForFrontend($product);
            return sendSuccessResponse('Data Found!', $product);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(),404);
        }
    }

    public function getPreOrderProductVariantImageAndPrice(
        $productSlug,
        $variantCode,
        $warehousePreOrderListingCode
    ){

        try{
            $singleProductResource['product_packaging_types'] =[];
            $warehouseCode = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore(getAuthStoreCode());

            $product = $this->productService->findOrFailProductBySlug($productSlug);
            $productVariant = $this->productVariantService->findOrFailVariantByProductCodeAndVariantCode($product->product_code,$variantCode);


            $warehousePreOrderListing = $this->warehousePreOrderService
                ->findOrFailWarehousePreOrderByWarehouseCode(
                    $warehousePreOrderListingCode,
                    $warehouseCode
                );


            if($warehousePreOrderListing->isPastFinalizationTime()){
                throw new Exception('Information not available after finalization time ends',402);
            }

            if(!StorePreOrderHelper::isProductInActiveWarehousePreOrderList(
                $warehouseCode,
                $warehousePreOrderListing->warehouse_preorder_listing_code,
                $product->product_code,
                $productVariant->product_variant_code
            )){
                throw new Exception('No Such Product-Variant Exist in the pre-order list');
            }

            $productVariantImages = $this->productVariantImageService->getImageListOfProductCodeAndVariantCode($product->product_code,$productVariant->product_variant_code);

            $productVariantImages=$this->productVariantImageService->concatImagePath($productVariantImages);

            if(!count($productVariantImages)>0){
                $productVariantBulkImages = $this->pvGroupBulkImageService->getGroupBulkImagesByGroupCode($productVariant->product_variant_group_code);
                $productVariantImages = $this->pvGroupBulkImageService->concatImagePath($productVariantBulkImages);
            }


            $preOrderPrice = (new ProductPriceHelper())->getPreOrderProductStorePrice(
                $warehouseCode,
                $warehousePreOrderListingCode,
                $product->product_code,
                $productVariant->product_variant_code
            );
            $microUnitRate =$preOrderPrice;

            $productUnitPackagingDetail = ProductUnitPackagingHelper::findProductPackagingDetail(
                $product->product_code,$productVariant->product_variant_code);

            $warehousePreOrderProduct = WarehousePreOrderProduct::where('warehouse_preorder_listing_code',$warehousePreOrderListingCode)
                ->where('product_code',$product->product_code)
                ->where('product_variant_code',$productVariant->product_variant_code)->firstOrFail();

            $disabledUnitList = PreOrderPackagingUnitDisableList::where('warehouse_preorder_product_code'
                ,$warehousePreOrderProduct->warehouse_preorder_product_code)
                ->pluck('unit_name')->toArray();

            if ($productUnitPackagingDetail){
                if ($productUnitPackagingDetail->micro_unit_code && !in_array('micro',$disabledUnitList)){
                    array_push($singleProductResource['product_packaging_types'],[
                        'package_code'=>$productUnitPackagingDetail->micro_unit_code,
                        'package_name'=>$productUnitPackagingDetail->micro_unit_name,
                        'price' =>$preOrderPrice,
                        'description' => ''
                    ]);
                }
                if ($productUnitPackagingDetail->unit_code && !in_array('unit',$disabledUnitList)){
                    if ($preOrderPrice != 'N/A'){
                        $preOrderPrice =roundPrice($productUnitPackagingDetail->micro_to_unit_value *$microUnitRate);
                    }

                    array_push($singleProductResource['product_packaging_types'],[
                        'package_code'=>$productUnitPackagingDetail->unit_code,
                        'package_name'=>$productUnitPackagingDetail->unit_name,
                        'price' =>$preOrderPrice,
                        'description' => 'One '. $productUnitPackagingDetail->unit_name. ' consists '.
                            $productUnitPackagingDetail->micro_to_unit_value. ' '.
                            $productUnitPackagingDetail->micro_unit_name
                    ]);
                }
                if ($productUnitPackagingDetail->macro_unit_code && !in_array('macro',$disabledUnitList)){
                    if ($preOrderPrice != 'N/A'){
                        $preOrderPrice =roundPrice($productUnitPackagingDetail->micro_to_unit_value *
                            $productUnitPackagingDetail->unit_to_macro_value *$microUnitRate);
                    }
                    array_push($singleProductResource['product_packaging_types'],[
                        'package_code'=>$productUnitPackagingDetail->macro_unit_code,
                        'package_name'=>$productUnitPackagingDetail->macro_unit_name,
                        'price' =>$preOrderPrice,
                        'description' => 'One '. $productUnitPackagingDetail->macro_unit_name. ' consists '.
                            $productUnitPackagingDetail->micro_to_unit_value*
                            $productUnitPackagingDetail->unit_to_macro_value. ' '.
                            $productUnitPackagingDetail->micro_unit_name
                    ]);
                }
                if ($productUnitPackagingDetail->super_unit_code && !in_array('super',$disabledUnitList)){
                    if ($preOrderPrice != 'N/A'){
                        $preOrderPrice =roundPrice($productUnitPackagingDetail->micro_to_unit_value *
                            $productUnitPackagingDetail->unit_to_macro_value *
                            $productUnitPackagingDetail->macro_to_super_value *$microUnitRate);
                    }
                    array_push($singleProductResource['product_packaging_types'],[
                        'package_code'=>$productUnitPackagingDetail->super_unit_code,
                        'package_name'=>$productUnitPackagingDetail->super_unit_name,
                        'price' =>$preOrderPrice,
                        'description' => 'One '. $productUnitPackagingDetail->super_unit_name. ' consists '.
                            $productUnitPackagingDetail->micro_to_unit_value*
                            $productUnitPackagingDetail->unit_to_macro_value *
                            $productUnitPackagingDetail->macro_to_super_value.' '.
                            $productUnitPackagingDetail->micro_unit_name
                    ]);
                }

            }

            $response['data'] = [
                'rate' => $microUnitRate,
                'is_taxable' => $product->is_taxable,
                'images' => $productVariantImages,
                'product_packaging_types' => $singleProductResource['product_packaging_types']
            ];

            return response()->json($response, 200);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),404);
        }
    }


    public function getRelatedPreOrderProductsOfWhPreOrderListingCode(
        $warehousePreOrderListingCode, $productCode){
        try{
            $authStoreCode =getAuthStoreCode();
            $warehouseCode = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore($authStoreCode);
            $filterParameters=[
                'warehouse_code' =>  [$warehouseCode],
                'warehouse_preorder_listing_code'=>$warehousePreOrderListingCode,
                'is_active'=> true
            ];
            $with=[
                //'warehousePreOrderListing',
                'product:product_code,product_name,slug,highlights,category_code,brand_code',
                'product.category:category_code,category_name',
                'product.brand:brand_code,brand_name',

                //'productVariant:product_variant_name',
            ];

            $warehousePreOrderListing = $this->warehousePreOrderService
                ->findOrFailWarehousePreOrderByWarehouseCode(
                    $warehousePreOrderListingCode,
                    $warehouseCode
                );


            if($warehousePreOrderListing->isPastFinalizationTime()){
                throw new Exception('Information not available after finalization time ends',402);
            }

            $preOrderProducts =WarehousePreOrderProductFilter::filterPaginatedWarehouseRelatedPreOrderProductsForStore($filterParameters,$productCode
                ,10,$with);
            return WarehousePreOrderRelatedProductResource::collection($preOrderProducts);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function getAssociatedPreOrderVariantDetails(
        $preOrderListingCode,$productCode,$variantValueCode,$variantDepth,$ancestorCode){

        try{

            $variantAssociatedValues = PreOrderVariantSelectionHelper::getAssociatedPreOrderVariantDetails(
                $preOrderListingCode,$productCode,$variantValueCode,$variantDepth+1,$ancestorCode
            );
            return sendSuccessResponse('Data Found',$variantAssociatedValues);

        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }

    }

    public function getSinglePreOrderProductsListViewDetails($warehousePreOrderListingCode,$productCode){

       try{
           $warehouseCode = null;
           $storeCode = getAuthGuardStoreCode();
           $warehouseCode = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore($storeCode);
           $warehousePreOrderListing = $this->warehousePreOrderService
               ->findOrFailWarehousePreOrderByWarehouseCode(
                   $warehousePreOrderListingCode,
                   $warehouseCode
               );


           if($warehousePreOrderListing->isPastFinalizationTime()){
               throw new Exception('Information not available after finalization time ends',402);
           }

           $this->productService->findOrFailProductByCode($productCode);
           $preOrderProductListDetails = PreOrderProductHelper::getSinglePreOrderProductListViewDetailsOfPreOrder(
               $warehousePreOrderListingCode,
               $storeCode,
               $productCode
           );

           return new SinglePreOrderProductListingCollection($preOrderProductListDetails);
       }catch (Exception $exception){
           return sendErrorResponse($exception->getMessage(),$exception->getCode());
       }


    }

    public function getAllPreOrderProductsListViewDetails($warehousePreOrderListingCode){

        try{

            $storeCode = getAuthGuardStoreCode();
            $warehouseCode = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore($storeCode);
            $warehousePreOrderListing = $this->warehousePreOrderService
                ->findOrFailWarehousePreOrderByWarehouseCode(
                    $warehousePreOrderListingCode,
                    $warehouseCode
                );


            if($warehousePreOrderListing->isPastFinalizationTime()){
                throw new Exception('Information not available after finalization time ends',402);
            }

            $preOrderProductListDetails = PreOrderProductHelper::getAllPreOrderProductListViewDetailsOfPreOrder(
                $warehousePreOrderListingCode,
                $storeCode
            );

            return new AllWarehousePreOrderProductsListingCollection($preOrderProductListDetails);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }

    }







}
