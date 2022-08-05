<?php

namespace App\Modules\Store\Services\PreOrder;

use App\Exceptions\Custom\PermissionDeniedException;
use App\Exceptions\Custom\ProductNotEligibleToOrderException;
use App\Exceptions\Custom\ProductNotPreOrderableException;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductHelper;
use App\Modules\AlpasalWarehouse\Models\PreOrder\PreOrderPackagingUnitDisableList;
use App\Modules\AlpasalWarehouse\Repositories\PreOrder\WarehousePreOrderProductRepository;
use App\Modules\AlpasalWarehouse\Repositories\PreOrder\WarehousePreOrderRepository;
use App\Modules\Cart\Repositories\CartRepository;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Store\Helpers\PreOrder\PreOrderProductPriceHelper;
use App\Modules\Store\Helpers\PreOrder\StorePreOrderHelper;
use App\Modules\Store\Helpers\StoreOrderHelper;
use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use App\Modules\Store\Models\PreOrder\StorePreOrderDetail;
use App\Modules\Store\Models\PreOrder\StorePreOrderDetailView;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderRepository;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Vendor\Repositories\VendorProductPackagingHistoryRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StorePreOrderService
{
    private $storePreOrderRepository;
    private $productRepository;
    private $warehousePreOrderRepository,$vendorProductPackagingHistoryRepository;
    private $storeRepository;
    private $storeBalance;

    public function __construct(
        ProductRepository $productRepository,
       StorePreOrderRepository $storePreOrderRepository,
       WarehousePreOrderRepository $warehousePreOrderRepository,
        WarehousePreOrderProductRepository $warehousePreOrderProductRepository,
        VendorProductPackagingHistoryRepository $vendorProductPackagingHistoryRepository,
        StoreRepository $storeRepository,
        StoreBalance $storeBalance
    )
    {
     $this->storePreOrderRepository = $storePreOrderRepository;
     $this->productRepository = $productRepository;
     $this->warehousePreOrderRepository = $warehousePreOrderRepository;
     $this->warehousePreOrderProductRepository = $warehousePreOrderProductRepository;
     $this->vendorProductPackagingHistoryRepository = $vendorProductPackagingHistoryRepository;
     $this->storeBalance = $storeBalance;
     $this->storeRepository = $storeRepository;
    }

    public function findorFailStorePreOrderByCode($storePreOrderCode,$with=[],$select='*'){
        return $this->storePreOrderRepository->getStorePreOrderByPreOrderCode($storePreOrderCode,$with,$select);
    }

    public function  getStorePreOrderDetails($store_order_pre_code,$filterParameters,$with=[],$select='*'){

        $storePreOrder = $this->storePreOrderRepository->getStorePreOrderByPreOrderCode(
                                    $store_order_pre_code,[
                                        'storePreOrderDispatchDetail',
                                        'warehousePreOrderListing:warehouse_preorder_listing_code,pre_order_name,start_time,end_time,finalization_time'
                                    ],$select
                                );
       // $storePreOrder = $this->storePreOrderRepository->getValidStorePreOrderByPreOrderCode($store_order_pre_code,$with,$select);

        if ($storePreOrder->store_code !== getAuthStoreCode()) {
            throw new PermissionDeniedException('Forbidden : Cannot See Pre Order Details', 403);
        }

        $storePreOrderDetail =  $this->newGetDetailsofStorePreOrderCode($storePreOrder->store_preorder_code,$filterParameters);

        $storePreOrderStatusLogs = $this->storePreOrderRepository->getStatusLogsofStorePreOrderCode(
            $storePreOrder->store_preorder_code,
            $with=[],
            ['store_preorder_status_log_code','store_preorder_code','status','remarks','created_at']
        );

        $storePreOrderDispatchDetails = $storePreOrder->storePreOrderDispatchDetail;

        return [
                'store_pre_order' => $storePreOrder,
                'store_pre_order_detail' => $storePreOrderDetail,
                'store_pre_order_status_logs'=>$storePreOrderStatusLogs,
                'store_pre_order_dispatch_details'=>$storePreOrderDispatchDetails
        ];
    }

    public function getDetailsofStorePreOrderCode($store_preorder_code,$filterParameters){

        //dd($filterParameters);

        $storePreOrderDetails =  StorePreOrderDetail::select(
            'store_preorder_details.store_preorder_detail_code',
            'store_preorder_details.store_preorder_code',
            'store_preorder_details.warehouse_preorder_product_code',
             DB::raw('(CASE
             WHEN product_variants.product_variant_name IS NOT NULL
             THEN
                CONCAT(products_master.product_name," (",product_variants.product_variant_name,")")
             ELSE
               products_master.product_name
             END
             )
                  as product_name'),
            'product_images.image',
            'store_preorder_details.quantity',
            'store_preorder_details.initial_order_quantity',
            'store_preorder_details.is_taxable',
            'store_preorder_details.created_at',
            'warehouse_preorder_products.is_active'
          )
            ->leftjoin('warehouse_preorder_products','warehouse_preorder_products.warehouse_preorder_product_code','=','store_preorder_details.warehouse_preorder_product_code')
            ->leftjoin('products_master','warehouse_preorder_products.product_code','=','products_master.product_code')
            ->leftjoin('product_variants','warehouse_preorder_products.product_variant_code','=','product_variants.product_variant_code')
            ->leftjoin('product_images',function($join){
                $join->on('product_images.product_code','=','products_master.product_code')
                    ->whereIn('product_images.id',function($query){
                        $query->select(DB::raw('MIN(product_images.id)'))
                            ->from('product_images')
                            ->groupBy('product_images.product_code');
                    });
            })
             ->addSelect(DB::raw('
               (mrp-(CASE wholesale_margin_type when "p" Then (wholesale_margin_value/100)*mrp Else wholesale_margin_value End)-
               (CASE retail_margin_type when "p" Then (retail_margin_value/100)*mrp Else retail_margin_value End))
               as store_price
             '))
            ->where('store_preorder_code',$store_preorder_code)

            ->when(isset($filterParameters['date_from']),function ($query) use($filterParameters){
                $query->whereDate('store_preorder_details.created_at','>=',date('y-m-d',strtotime($filterParameters['date_from'])));
            })
            ->when(isset($filterParameters['date_to']),function ($query) use($filterParameters){
                $query->whereDate('store_preorder_details.created_at','<=',date('y-m-d',strtotime($filterParameters['date_to'])));
            })
            ->when(isset($filterParameters['is_active']),function($query) use ($filterParameters){
                $query->where('warehouse_preorder_products.is_active',$filterParameters['is_active']);
            })
            ->groupBy('store_preorder_details.store_preorder_detail_code')
            ->when(isset($filterParameters['product_name']),function($query) use($filterParameters){
                $query->having('product_name','like','%'.$filterParameters['product_name'].'%');
            })
            ->orderBy('store_preorder_details.created_at','DESC')
            ->get();

      return  $storePreOrderDetails;
    }

    public function newGetDetailsofStorePreOrderCode($store_preorder_code,$filterParameters){

        $storePreOrderDetails =  StorePreOrderDetailView::select(
            'store_pre_order_detail_view.store_preorder_detail_code',
            'store_pre_order_detail_view.store_preorder_code',
            'store_pre_order_detail_view.warehouse_preorder_product_code',
            'store_pre_order_detail_view.delivery_status',
            'package_types.package_name',
            'old_package_types.package_name as old_package_name',

            DB::raw('(CASE
         WHEN product_variants.product_variant_name IS NOT NULL
         THEN
            CONCAT(products_master.product_name," (",product_variants.product_variant_name,")")
         ELSE
           products_master.product_name
         END
         )
              as product_name'),
            'product_images.image',
            'store_pre_order_detail_view.quantity',
            'store_pre_order_detail_view.initial_order_quantity',
            'store_pre_order_detail_view.is_taxable',
            'store_pre_order_detail_view.unit_rate',
            'store_pre_order_detail_view.created_at',
            'warehouse_preorder_products.is_active'
        )
            ->join('warehouse_preorder_products','warehouse_preorder_products.warehouse_preorder_product_code','=','store_pre_order_detail_view.warehouse_preorder_product_code')
            ->join('products_master','warehouse_preorder_products.product_code','=','products_master.product_code')
            ->leftjoin('package_types','package_types.package_code','=','store_pre_order_detail_view.package_code')
            ->leftJoin('product_package_details',
                 'product_package_details.product_code','=','products_master.product_code')
            ->leftJoin('package_types as old_package_types','old_package_types.package_code','=','product_package_details.package_code')
            ->leftjoin('product_images',function($join){
                $join->on('product_images.product_code','=','products_master.product_code')
                    ->whereIn('product_images.id',function($query){
                        $query->select(DB::raw('MIN(product_images.id)'))
                            ->from('product_images')
                            ->groupBy('product_images.product_code');
                    });
            })->leftjoin('product_variants','warehouse_preorder_products.product_variant_code','=','product_variants.product_variant_code')
            ->where('store_preorder_code',$store_preorder_code)
            ->when(isset($filterParameters['date_from']),function ($query) use($filterParameters){
                $query->whereDate('store_pre_order_detail_view.created_at','>=',date('y-m-d',strtotime($filterParameters['date_from'])));
            })
            ->when(isset($filterParameters['date_to']),function ($query) use($filterParameters){
                $query->whereDate('store_pre_order_detail_view.created_at','<=',date('y-m-d',strtotime($filterParameters['date_to'])));
            })
            ->when(isset($filterParameters['is_active']),function($query) use ($filterParameters){
                $query->where('warehouse_preorder_products.is_active',$filterParameters['is_active']);
            })
            ->groupBy('store_pre_order_detail_view.store_preorder_detail_code')
            ->when(isset($filterParameters['product_name']),function($query) use($filterParameters){
                $query->having('product_name','like','%'.$filterParameters['product_name'].'%');
            })
            ->orderBy('store_pre_order_detail_view.created_at','DESC')
            ->whereNULL('store_pre_order_detail_view.deleted_at')
            ->get();
        return  $storePreOrderDetails;
    }



    public function saveProductInPreOrder($validatedPreOrderData)
    {
        try{
            $store = getAuthStore();
            $storeCode = $store->store_code;
            if ($store->has_purchase_power == 0) {
                throw new Exception('The store has no permission to order');
            }

            $warehouseCode = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore($storeCode);

            $warehousePreOrder = $this->warehousePreOrderRepository->findOrFailPreOrderByWarehouseCode(
                $validatedPreOrderData['wh-preorder-listing-code'],$warehouseCode
            );

            if($warehousePreOrder->isCancelled()){
                throw new Exception('Cannot order product:pre-order has been cancelled');
            }
            $productSlug = $validatedPreOrderData['product_slug'];
            $productVariantCode = null;
            $product = $this->productRepository->findOrFailProductBySlug($productSlug);
            $productCode = $product->product_code;
            $productVariantName ='';
            if ($product->hasVariants()) {
                if(!isset($validatedPreOrderData['combination_name'])){
                    $error = ValidationException::withMessages([
                        'combination_name' => ['Required Variant Selection']
                    ]);
                    throw new Exception('Variant selection is required',400);
                }
                $productVariantName = $validatedPreOrderData['combination_name'];
                $productVariant = $product->productVariants()
                    ->where('product_variant_code', $productVariantName)
                    ->first();

                if (!$productVariant) {
                    throw new Exception('No Such Variant Found !');
                }
                $productVariantCode = $productVariant->product_variant_code;
            }



//            if (!ProductUnitPackagingHelper::isProductPackagedByPackageCode(
//                $validatedPreOrderData['package_code'],$product->product_code,$productVariantCode)){
//                throw new Exception('Add to PreOrder failed: package type does not exist for product.');
//            }

            $productPackagingHistory = $this->vendorProductPackagingHistoryRepository
                                            ->getLatestProductPackagingHistoryByPackageCode(
                                                 $validatedPreOrderData['package_code'],
                                                 $productCode,
                                                 $productVariantCode
                                       );

            if (!$productPackagingHistory){
                throw new Exception('Order failed: package type does not exist for '.
                    $product->product_name. $productVariantName);
            }
            $validatedPreOrderData['product_packaging_history_code'] = $productPackagingHistory->product_packaging_history_code;

            $validatedPreOrderData['product_code'] = $productCode;
            $validatedPreOrderData['product_variant_code'] = $productVariantCode;
            $validatedPreOrderData['warehouse_code'] = $warehouseCode;
            $validatedPreOrderData['store_code'] = $storeCode;


            $preOrderProduct = StorePreOrderHelper::isProductInActiveWarehousePreOrderList(
                $validatedPreOrderData['warehouse_code'],
                $validatedPreOrderData['wh-preorder-listing-code'],
                $validatedPreOrderData['product_code'],
                $validatedPreOrderData['product_variant_code']
            );
          //  dd($preOrderProduct['warehouse_preorder_product_code']);


            if(!$preOrderProduct){
                throw new ProductNotPreOrderableException(
                    'Sorry ! this product is not pre-orderable right now',
                    [
                        'product_name' => $product->product_name
                    ]
                );
            }

            $storePreOrderInWPL = StorePreOrderHelper::getStorePreOrderInWhPreOrderListingCode(
                $validatedPreOrderData['wh-preorder-listing-code'],
                $validatedPreOrderData['store_code']
            );

            if($storePreOrderInWPL && $storePreOrderInWPL->early_finalized){
                throw new Exception('You order has been early finalized .you cannot add products to this preorder!');
            }

            if($storePreOrderInWPL && $storePreOrderInWPL->early_cancelled){
                throw new Exception('You order has been early Cancelled.you cannot add products to this preorder or create new preorder!');
            }

//            $existingPreOrderDetail = $this->storePreOrderRepository
//                ->getStorePreOrderDetailByProductCodeAndVariantCode(
//                $validatedPreOrderData['wh-preorder-listing-code'],
//                $validatedPreOrderData['store_code'],
//                $validatedPreOrderData['product_code'],
//                $validatedPreOrderData['product_variant_code'],
//                $validatedPreOrderData['package_code']
//            );

            $validatedPreOrderData['warehouse_preorder_product_code'] = $preOrderProduct->warehouse_preorder_product_code;


            $existingPreOrderDetail = null;
            if($storePreOrderInWPL){
                $existingPreOrderDetail = $this->storePreOrderRepository
                    ->getExistingStorePreOrderDetail(
                        $storePreOrderInWPL->store_preorder_code,
                        $validatedPreOrderData['warehouse_preorder_product_code'],
                        $validatedPreOrderData['package_code']
                    );
            }
//            $existingPreOrderDetail = $this->storePreOrderRepository
//                                           ->getExistingStorePreOrderDetail(
//                                               $storePreOrderInWPL->store_preorder_code,
//                                               $validatedPreOrderData['warehouse_preorder_product_code'],
//                                               $validatedPreOrderData['package_code']
//                                           );


            $currentUpdateableQuantity = (int) $validatedPreOrderData['quantity'];

            if($existingPreOrderDetail){
                if($existingPreOrderDetail->storePreOrder->status != 'pending'){
                    throw new Exception('Cannot add product !');
                }
                $validatedPreOrderData['quantity']= (int) $existingPreOrderDetail->quantity + (int) $validatedPreOrderData['quantity'];
            }

            //$preOrderProduct = $this->warehousePreOrderProductRepository->findPreOrderProduct($validatedPreOrderData['warehouse_preorder_product_code']);

            //for disabled unit list
            $disabledUnitList = PreOrderPackagingUnitDisableList::where('warehouse_preorder_product_code'
                , $validatedPreOrderData['warehouse_preorder_product_code'])
                ->pluck('unit_name')->toArray();

            if( $validatedPreOrderData['package_code'] == $productPackagingHistory->micro_unit_code){
                $orderedPackageName ='micro';
            }elseif ($validatedPreOrderData['package_code'] == $productPackagingHistory->unit_code){
                $orderedPackageName ='unit';
            }
            elseif ($validatedPreOrderData['package_code'] == $productPackagingHistory->macro_unit_code){
                $orderedPackageName ='macro';
            }
            elseif ($validatedPreOrderData['package_code'] == $productPackagingHistory->super_unit_code){
                $orderedPackageName ='super';
            }else{
                throw new Exception('Invalid order package type.');
            }

            if (in_array($orderedPackageName,$disabledUnitList)){
                throw new Exception('Invalid order package type.');
            }

            //end of disabled unit list
//
//            if (!$preOrderProduct) {
//                throw new Exception('No Such product Found !');
//            }


            if(!is_null($preOrderProduct['min_order_quantity'])){
                 if($validatedPreOrderData['quantity'] < (int) $preOrderProduct['min_order_quantity']){
                        throw new Exception('cannot add product with quantity less than minimum order quantity : '.(int) $preOrderProduct['min_order_quantity'].'',400);
                 }
            }
            if(!is_null($preOrderProduct['max_order_quantity'])){
               if($validatedPreOrderData['quantity'] > (int) $preOrderProduct['max_order_quantity']){
                   throw new Exception('cannot update product with quantity greater than maximum order quantity : '.(int) $preOrderProduct['max_order_quantity'].'',400);
               }
            }



            //$validatedPreOrderData['user_code'] = getAuthUserCode();

            $validatedPreOrderData['is_taxable'] = $product->is_taxable;

            $microRate = PreOrderProductPriceHelper::getMicroPriceOfPreOrderableProduct(
                                                $validatedPreOrderData['warehouse_preorder_product_code']
                                              );

            $packagePrice = PreOrderProductPriceHelper::getPackagePriceOfPreOrderableProduct(
                                               $validatedPreOrderData['package_code'],
                                               $productPackagingHistory->product_packaging_history_code,
                                               $microRate
                                             );

            //$currentActiveBalance = StoreTransactionHelper::getStoreCurrentBalance($storeCode);


           // $store = $this->storeRepository->findOrFailStoreByCode($validatedPreOrderData['store_code']);
            $currentActiveBalance = $this->storeBalance->getStoreActiveBalance($store);

            $updateableQuantityPrice =(int) $currentUpdateableQuantity * $packagePrice;

            if($currentActiveBalance < $updateableQuantityPrice){
                throw new Exception('You do not have sufficient balance to order this product for desired quantity',400);
            }

            DB::beginTransaction();
            $productAddedToPreOrder = $this->storePreOrderRepository->saveProductInPreOrder(
                $validatedPreOrderData,
                $storePreOrderInWPL,
                $existingPreOrderDetail
            );
            DB::commit();
            return $productAddedToPreOrder;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }


    public function updatePreOrderProductQuantity($preOrderData){
        try{
            //dd($preOrderData);

//            $updatablePreOrderDetail = $this->storePreOrderRepository
//                ->getUpdatablePreOrderDetail(
//                $preOrderData['store_preorder_code'],
//                $preOrderData['warehouse_preorder_product_code']
//            );

            $with = ['storePreOrder'];
            $updatablePreOrderDetail = $this->storePreOrderRepository
                ->getNewUpdatablePreOrderDetail(
                $preOrderData['store_preorder_detail_code'],$with
            );

//            if(isset($updatablePreOrderDetail) && $updatablePreOrderDetail->storePreOrder->early_finalized){
//                throw new Exception('You order has been early finalized .you cannot add products to this preorder!');
//            }
//
//            if(isset($updatablePreOrderDetail) && $updatablePreOrderDetail->storePreOrder->early_cancelled){
//                throw new Exception('You order has been early Cancelled .you cannot add products to this preorder!');
//            }

            if(!$updatablePreOrderDetail){
                throw  new Exception(
                    'You cannot update the product
                 since the finalization time has ended
                 or this product has been set inactive by warehouse !'
                ,403);
            }

            $preOrderProduct = $this->warehousePreOrderProductRepository
                ->findPreOrderProduct(
                    $updatablePreOrderDetail->warehouse_preorder_product_code
                );

            if (!$preOrderProduct) {
                throw new Exception('No Such product Found !');
            }


                if(!is_null($preOrderProduct['min_order_quantity'])){
                    if($preOrderData['initial_order_quantity'] < (int) $preOrderProduct['min_order_quantity']){
                        throw new Exception('cannot update product with quantity less than minimum order quantity : '.(int) $preOrderProduct['min_order_quantity'].'',400);
                    }
                }
                if(!is_null($preOrderProduct['max_order_quantity'])){
                    if($preOrderData['initial_order_quantity'] > (int) $preOrderProduct['max_order_quantity']){
                        throw new Exception('cannot update product with quantity greater than maximum order quantity : '.(int) $preOrderProduct['max_order_quantity'].'',400);
                    }
                }


           // $currentUpdateableQuantity = $updatablePreOrderDetail->quantity - $preOrderData['initial_order_quantity'];

            $currentUpdateableQuantity =  $preOrderData['initial_order_quantity'] - $updatablePreOrderDetail->quantity;

            $microRate = PreOrderProductPriceHelper::getMicroPriceOfPreOrderableProduct(
                $updatablePreOrderDetail->warehouse_preorder_product_code
            );

            $packagePrice = PreOrderProductPriceHelper::getPackagePriceOfPreOrderableProduct(
                $updatablePreOrderDetail->package_code,
                $updatablePreOrderDetail->product_packaging_history_code,
                $microRate
            );

//            $currentActiveBalance = StoreTransactionHelper::getStoreCurrentBalance(
//               getAuthStoreCode()
//            );
            //$store = $this->storeRepository->findOrFailStoreByCode(getAuthStoreCode());
            $currentActiveBalance =$this->storeBalance->getStoreActiveBalance(getAuthStore());

            $updateableQuantityPrice =(int) $currentUpdateableQuantity * $packagePrice;

            if($currentActiveBalance < $updateableQuantityPrice){
                throw new Exception('You do not have sufficient balance to order this product',400);
            }


            DB::beginTransaction();

            $updatablePreOrderDetail = $this->storePreOrderRepository
                                          ->updateQuantityInPreOrder(
                                              $updatablePreOrderDetail,
                                              $preOrderData['initial_order_quantity']
                                          );
            DB::commit();
            return $updatablePreOrderDetail;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function deletePreOrderProduct($storePreOrderDetailCode)
    {
        try{
            $storePreOrderDetail = $this->storePreOrderRepository
                                        ->getDeleteablePreOrderDetail(
                                            $storePreOrderDetailCode
                                        );

            if(!$storePreOrderDetail){
                throw new Exception('Cannot delete the pre ordered product after the pre order has finished !');
            }

            DB::beginTransaction();
            $storePreOrderDetail =  $this->storePreOrderRepository
                                       ->deletePreOrderProduct($storePreOrderDetail);
            DB::commit();
            return $storePreOrderDetail;

        }catch(Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }



}
