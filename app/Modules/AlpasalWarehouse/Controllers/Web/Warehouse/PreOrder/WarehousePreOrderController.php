<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\PreOrder;

use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderHelper;
use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderProductFilter;
use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderListing;
use App\Modules\AlpasalWarehouse\Requests\PreOrder\ClonePreOrderListingByWHRequest;
use App\Modules\AlpasalWarehouse\Requests\PreOrder\WarehousePreOrderCancelRequest;
use App\Modules\AlpasalWarehouse\Requests\PreOrder\WarehousePreOrderPriceSettingCreateRequest;
use App\Modules\AlpasalWarehouse\Requests\PreOrder\WarehousePreOrderStoreRequest;
use App\Modules\AlpasalWarehouse\Requests\PreOrder\WarehousePreOrderUpdateRequest;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Brand\Services\BrandService;
use App\Modules\Category\Services\CategoryService;
use App\Modules\Product\Services\ProductService;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use App\Modules\Vendor\Services\VendorService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehousePreOrderController extends BaseController
{
    public $title = 'Alpasal Warehouse PreOrder';
    public $base_route = 'warehouse.warehouse-pre-orders.';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view='warehouse.warehouse-pre-orders.';

    private $warehousePreOrderService,$vendorService,$categoryService,$brandService;
    private $productService;

    public function __construct(WarehousePreOrderService $warehousePreOrderService,
                                VendorService $vendorService,
                                CategoryService $categoryService,
                                BrandService $brandService,ProductService $productService)
    {
        $this->middleware('permission:View List Of WH Pre Orders', ['only' => [
            'index'
        ]]);
        $this->middleware('permission:Create WH Pre Order', ['only' => [
            'create',
            'store'
        ]]);
        $this->middleware('permission:Edit WH Pre Order', ['only' => [
            'edit',
            'update'
        ]]);
        $this->middleware('permission:Delete WH Pre Order', ['only' => 'destroy']);
        $this->middleware('permission:Cancel WH Pre Order', ['only' => 'cancelPreOrder']);
        $this->middleware('permission:Finalize WH Pre Order', ['only' => 'finalizePreOrder']);
        $this->middleware('permission:Change The Status Of WH Pre Order', ['only' => 'togglePreOrderStatus']);

        $this->warehousePreOrderService = $warehousePreOrderService;
        $this->vendorService = $vendorService;
        $this->categoryService = $categoryService;
        $this->brandService = $brandService;
        $this->productService = $productService;
    }

    public function index(Request $request){
        try{
            $filterParameters = [
                'pre_order_name'=> $request->get('pre_order_name')
            ];
            $authWarehouseCode = getAuthWarehouseCode();
            $warehousePreOrders = $this->warehousePreOrderService->getPaginatedPreOrdersOfWarehouse($authWarehouseCode,$filterParameters,10);

            return view($this->loadViewData($this->module.$this->view.'index'),
                compact('warehousePreOrders','filterParameters'));
        }catch (Exception $exception){
            return redirect()->route('warehouse.dashboard')->with('danger', $exception->getMessage());
        }
    }

    public function create()
    {
       /* $authStoreCode ='S1000';
        $filterParameters=[
            'warehouse_code' => ['AW1001'],
            'warehouse_preorder_listing_code'=>'WPLC1000',
            'is_active'=> true
        ];
        $with=[
            'warehousePreOrderListing',
            'product.images',
            'product:product_code,product_name,slug,highlights',
            'productVariant:product_variant_name',
        ];
        $preOrderProducts =WarehousePreOrderProductFilter::filterPaginatedWarehousePreOrderProductsForStore(
            $authStoreCode,$filterParameters,20,$with);

        dd($preOrderProducts);*/
        try{
            return view($this->loadViewData($this->module.$this->view.'create'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function store(WarehousePreOrderStoreRequest $request)
    {
        try{
            $validated = $request->validated();
            $this->warehousePreOrderService->storeWarehousePreOrder($validated);
            return redirect()->back()->with('success', $this->title .' created successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function edit($warehousePreOrderCode){
        try{
           $warehousePreOrder = $this->warehousePreOrderService->findOrFailWarehousePreOrderByWarehouseCode($warehousePreOrderCode,getAuthWarehouseCode());

           $isPastStartTime=false;
           if ($warehousePreOrder->isFinalized()){
               throw new Exception('Cannot update after the pre-order listing has been finalized.');
           }
            if ($warehousePreOrder->isCancelled()){
                throw new Exception('Cannot update: pre-order was cancelled.');
            }
            if ($warehousePreOrder->isPastStartTime()){
                $isPastStartTime = true;
            }

            return view($this->loadViewData($this->module.$this->view.'edit'),compact('warehousePreOrder',
                'isPastStartTime'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function update(WarehousePreOrderUpdateRequest $request,$warehousePreOrderCode){
        try{
            $validated= $request->validated();
            $warehousePreOrder = $this->warehousePreOrderService->updateWarehousePreOrder($validated,$warehousePreOrderCode);
            return redirect()->back()->with('success', $this->title .' updated successfully');
        }catch (Exception $exception){
            return redirect()->back()
                ->with('danger', $exception->getMessage())
                ->withInput();
        }
    }

    public function togglePreOrderStatus($code){
        try{
            $this->warehousePreOrderService->updateActiveStatus($code);
            return redirect()->back()->with('success', $this->title .' status updated successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

//    public function finalizePreOrders(){
//        try{
//            $this->warehousePreOrderService->finalizeWarehousePreOrders();
//            return redirect()->back()->with('success', $this->title .' finalized successfully');
//        }catch (Exception $exception){
//            return redirect()->route($this->base_route.'index')->with('danger', $exception->getMessage());
//        }
//    }

    public function finalizePreOrder($warehousePreOrderListingCode){
        try{
            $warehousePreOrder = $this->warehousePreOrderService->finalizeWarehousePreOrderWithNotification($warehousePreOrderListingCode);
            return redirect()->back()->with('success', 'Pre Order : '.$warehousePreOrder->pre_order_name.' finalized successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function cancelPreOrder(WarehousePreOrderCancelRequest $request,$warehousePreOrderListingCode){
        try{
            //dd(1);
            $validated = $request->validated();
            $this->warehousePreOrderService->cancelWarehousePreOrder($warehousePreOrderListingCode,$validated);
            $request->session()->flash('success', 'Pre-order cancelled');
            //return redirect()->back()->with('success', $this->title .' cancelled.');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function destroy($warehousePreOrderCode)
    {
        try{
            $this->warehousePreOrderService->deleteWarehousePreOrder($warehousePreOrderCode);
            return redirect()->back()->with('success', $this->title .'deleted successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }


    public function cloneWHPreOrderListing(
        ClonePreOrderListingByWHRequest $request,
        $whPreOrderListingCode){
        try{
            $validated = $request->validated();
            $validated['wh_preorder_listing_code'] = $whPreOrderListingCode;
            $this->warehousePreOrderService->cloneWhPreOrderListing($validated);
            return redirect()->back()->with('success', $this->title .'Cloned successfully from  ('.$whPreOrderListingCode.') ');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

}
