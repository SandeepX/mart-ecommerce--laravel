<?php


namespace App\Modules\Store\Controllers\Api\Front\PreOrder;


use App\Http\Controllers\Controller;
use App\Exceptions\Custom\PermissionDeniedException;
use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderProductFilter;
use App\Modules\AlpasalWarehouse\Resources\WarehousePreOrderProductCollection;
use App\Modules\AlpasalWarehouse\Resources\WarehousePreOrderResource;
use App\Modules\Category\Services\CategoryService;
use App\Modules\Product\Resources\MinimalProductCollection;
use App\Modules\Store\Helpers\PreOrder\PreorderProductCategoryHelper;
use App\Modules\Store\Requests\PreOrder\StorePreOrderCreateRequest;
use App\Modules\Store\Resources\StorePreOrder\MinimalStorePreOrderDetailResource;
use App\Modules\Store\Resources\StorePreOrder\StorePreOrderListingCollection;
use App\Modules\Store\Resources\StorePreOrder\StorePreOrderListingResource;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderService;
use App\Modules\Store\Helpers\PreOrder\StorePreOrderHelper;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use App\Modules\Store\Services\PreOrder\StorePreOrderService;
use App\Modules\Store\Services\StoreService;
use App\Modules\Store\Transformers\SingleStorePreOrderTransformer;
use App\Modules\Store\Requests\PreOrder\StorePreOrderUpdateRequest;
use App\Modules\Store\Models\PreOrder\StorePreOrderDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class StorePreOrderControllerApi extends Controller
{

    private $warehousePreOrderService,$storePreOrderService,$categoryService,$storeService;

    public function __construct(
        WarehousePreOrderService $warehousePreOrderService,
        StorePreOrderService $storePreOrderService,
        CategoryService $categoryService,
        StoreService  $storeService
    ){
        $this->warehousePreOrderService = $warehousePreOrderService;
        $this->storePreOrderService = $storePreOrderService;
        $this->categoryService = $categoryService;
        $this->storeService = $storeService;
    }



    public function getWarehousePreOrdersDateForStore(){
        try{
           $warehouseCode= StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore(getAuthStoreCode());
           $with=['storePreOrders'=>function($query){
               $query->where('store_code',getAuthStoreCode());
           }];
            $preOrders =$this->warehousePreOrderService
                ->getDisplayableWarehousePreOrdersByWarehouseCode($warehouseCode,$with);
            $preOrders = WarehousePreOrderResource::collection($preOrders);
            return sendSuccessResponse('Data Found', $preOrders);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getWHPreOrderListingInfo($warehousePreOrderListingCode){
        try{
            $warehouseCode= StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore(getAuthStoreCode());

            $warehousePreOrderListing = $this->warehousePreOrderService
                ->findOrFailWarehousePreOrderByWarehouseCode(
                    $warehousePreOrderListingCode,
                    $warehouseCode
                );


            if($warehousePreOrderListing->isPastFinalizationTime()){
                throw new Exception(
                    'Information not available after finalization time ends',
                    402);
            }
            $warehousePreOrderListing = new WarehousePreOrderResource($warehousePreOrderListing);
            return sendSuccessResponse('Data Found', $warehousePreOrderListing);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getWarehousePreOrderProductsForStore(Request $request,$warehousePreOrderListingCode){
        try{
            $authStoreCode =getAuthStoreCode();
            $requestedCategories = $request->get('cat_selected');
            $categoryCodes=[];
            if ($requestedCategories){
                $requestedCategories = convertToArray($requestedCategories);
                $categories = $this->categoryService->getCategoriesBySlugs($requestedCategories);

                $categoryCodes = $categories->pluck('category_code')->toArray();
            }
            $minPrice = $request->get('min_price');
            $maxPrice = $request->get('max_price');

            $filterParameters=[
                'warehouse_codes' =>  [StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore($authStoreCode)],
                'warehouse_preorder_listing_code'=>$warehousePreOrderListingCode,
                'category_codes' =>$categoryCodes,
                'min_price'=>$minPrice,
                'max_price'=>$maxPrice,
                'is_active'=> true,
                'product_name'=>$request->get('product_name')
            ];
            $with=[
                //'warehousePreOrderListing',
                'product.images',
                'product:product_code,product_name,slug,highlights,brand_code,category_code',
              //  'productVariant:product_variant_name',
            ];
            $preOrderProducts =WarehousePreOrderProductFilter::filterPaginatedWarehousePreOrderProductsForStore(
                $authStoreCode,$filterParameters,20,$with);
            return new WarehousePreOrderProductCollection($preOrderProducts);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function saveProductInPreOrderByStore(
        StorePreOrderCreateRequest $request,
         $whPreOrderListingCode
    ){
        $validatedData = $request->all();
        $validatedData['wh-preorder-listing-code'] = $whPreOrderListingCode;
        try {
            $preOrderProduct = $this->storePreOrderService->saveProductInPreOrder($validatedData);
            $preOrderProduct = new MinimalStorePreOrderDetailResource($preOrderProduct);
            return sendSuccessResponse('Product Saved In PreOrder',$preOrderProduct);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }


    public function getAmountGroupingsOfStorePreOrders(){
       return sendSuccessResponse(
           'Amounts Fetched ',
           StorePreOrderHelper::getAmountGroupingsOfStorePreOrders(getAuthStoreCode())
       );
    }

    public function getStorePreOrders(Request $request){

        if($request->payment_status == 'paid'){
            $payment_status =  1;
        }
        elseif($request->payment_status == 'unpaid'){
            $payment_status = 0;
        }
        else{
            $payment_status =  null;
        }

        try{
            $filterParameters = [
                'store_preorder_code'=> $request->get('store_preorder_code'),
                'start_time'=>$request->get('start_time'),
                'end_time'=>$request->get('end_time'),
                'total_amount'=>$request->get('total_amount'),
                'payment_status'=>$payment_status,
                'price_condition'=>$request->get('price_condition'),
                'total_price'=>$request->get('total_price'),
            ];
            $storeCode = getAuthStoreCode();
           // $preOrderTargets=StorePreOrderHelper::getTargets();
            $storePreOrderListing = StorePreOrderHelper::newfilterStorePreOrder($storeCode,$filterParameters,10);
          //  dd($storePreOrderListing[0]->warehousePreOrderListing);
//
//             foreach(collect($storePreOrderListing) as $key=>$data)
//             {
//                 foreach(collect($preOrderTargets) as $item=>$value)
//                 {
//                    if($data->warehouse_preorder_listing_code==$value->warehouse_preorder_listing_code)
//                    {
//                        $storePreOrderListing[$key]['total_group_target']=$preOrderTargets[$item]->total_group_target;
//                        $storePreOrderListing[$key]['total_individual_target']=$preOrderTargets[$item]->total_individual_target;
//                        $storePreOrderListing[$key]['total_group_order']=$preOrderTargets[$item]->total_group_order;
//                    }
//                 }
//             }
//            $storePreOrderListing=$this->paginate($storePreOrderListing);


            return  new StorePreOrderListingCollection($storePreOrderListing);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getStorePreOrderDetails(Request $request,$store_preorder_code)
    {

        if($request->is_active == 'yes'){
          $is_active =  1;
        }
        elseif($request->is_active == 'no'){
           $is_active = 0;
        }
        else{
          $is_active =  null;
        }

        $filterParameters = [
            'product_name' => $request->product_name,
            'date_from'=>$request->date_from,
            'date_to'=>$request->date_to,
            'is_active' => $is_active
        ];

        try {
          //  $targets=StorePreOrderHelper::getPreOrderTargetsOfDetail($store_preorder_code);
            $storePreOrderDetails = $this->storePreOrderService->getStorePreOrderDetails($store_preorder_code,$filterParameters,['warehousePreOrderListing']);
            $storePreOrderDetails = (new SingleStorePreOrderTransformer($storePreOrderDetails))->transform();

            return sendSuccessResponse('Data Found!', $storePreOrderDetails,[],JSON_NUMERIC_CHECK);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function updatePreOrderProductQuantity(
        StorePreOrderUpdateRequest $request,
        $storePreOrderDetailCode
       # $warehousePreOrderProductCode
    )
    {
        try{
            $validatedData = $request->validated();

            $preOrderData = [
                'initial_order_quantity'=>$validatedData['initial_order_quantity'],
                'store_preorder_detail_code' =>$storePreOrderDetailCode
               # 'warehouse_preorder_product_code' => $warehousePreOrderProductCode
            ];


            $updatablePreOrderDetail = $this->storePreOrderService->updatePreOrderProductQuantity(
                $preOrderData
            );
            $updatablePreOrderDetail = new MinimalStorePreOrderDetailResource($updatablePreOrderDetail);
            return sendSuccessResponse('Pre Order Product Updated',$updatablePreOrderDetail);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function deletePreOrderProductDetail($storePreOrderDetailCode)
    {
        try{
           $this->storePreOrderService->deletePreOrderProduct($storePreOrderDetailCode);
          return sendSuccessResponse('Pre-order Product Deleted Successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function getAllPreOrderCategoryByFilter(Request $request)
    {

        try {
            $requestedCategories = $request->get('category_slugs');
            //dd($requestedCategories);
            if (!$request->filled('category_slugs')) {
                throw new Exception('No Categories Selection');
            }

            $requestedCategories = convertToArray($requestedCategories);

            $minPrice = $request->get('min_price');
            $maxPrice = $request->get('max_price');
            $warehouseListingCode = $request->get('warehouse_pre_order_listing_code');

            $categories = $this->categoryService->getCategoriesBySlugs($requestedCategories);

            $categoryCodes = $categories->pluck('category_code')->toArray();

            $warehouseCode = PreorderProductCategoryHelper::getWarehouseCode($warehouseListingCode);

            $filterParameters =[
                'category_codes' =>$categoryCodes,
                'min_price'=>$minPrice,
                'max_price'=>$maxPrice,
                'warehouse_code' => $warehouseCode,
            ];
           // dd($filterParameters);

            $products = PreorderProductCategoryHelper::filterProductsByParameters($filterParameters);
            //dd($products);
            return  new MinimalProductCollection($products);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  400);
        }
    }
    public function paginate($items, $perPage = 10, $page = null,
                             $baseUrl = null,
                             $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ?
            $items : Collection::make($items);

        $lap = new LengthAwarePaginator($items->forPage($page, $perPage),
            $items->count(),
            $perPage, $page, $options);

        if ($baseUrl) {
            $lap->setPath($baseUrl);
        }

        return $lap;
    }
}


