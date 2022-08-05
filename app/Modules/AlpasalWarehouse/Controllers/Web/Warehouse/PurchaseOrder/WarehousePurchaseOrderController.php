<?php

namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\PurchaseOrder;

use App\Modules\AlpasalWarehouse\Helpers\WarehousePurchaseOrderFilter;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseOrder;
use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseReturn;
use App\Modules\AlpasalWarehouse\Requests\WarehousePurchaseOrderReceivedQuantityUpdateRequest;
use App\Modules\AlpasalWarehouse\Requests\WarehousePurchaseOrderRequest;
use App\Modules\AlpasalWarehouse\Requests\WarehousePurchaseOrderReturnRequest;
use App\Modules\AlpasalWarehouse\Services\Bill\WarehouseOrderBillService;
use App\Modules\AlpasalWarehouse\Services\PurchaseOrder\WarehousePurchaseOrderService;
use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Brand\Services\BrandService;
use App\Modules\Category\Services\CategoryService;
use App\Modules\Product\Helpers\ProductFilter;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Vendor\Services\VendorService;
use Exception;
use Illuminate\Http\Request;

class WarehousePurchaseOrderController extends BaseController
{
    public $title = 'Alpasal Warehouse Purchase Order';
    public $base_route = 'warehouse.warehouse-purchase-orders.';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view='warehouse.warehouse-purchase-orders.';
    private $warehousePurchaseOrderService;
    private $warehouseService;
    private $vendorService;
    private $categoryService;
    private $brandService,$warehouseOrderBillService;

    public function __construct(
        WarehousePurchaseOrderService $warehousePurchaseOrderService,
        WarehouseService $warehouseService,
        VendorService $vendorService,
        CategoryService $categoryService,
        BrandService $brandService,WarehouseOrderBillService $warehouseOrderBillService,
        WarehouseProductMaster $warehouseproductmaster

        )
    {
        $this->middleware('permission:View List Of WH Purchase Orders', ['only' => 'index', 'warehousePurchaseOrderList']);
        $this->middleware('permission:Add New WH Purchase Order', [
            'only' =>
                'create',
                'store',
                'edit'
        ]);
        $this->middleware('permission:Show WH Purchase Order Detail', [
            'only' =>
                'show',
                'generateWarehousePurchaseOrderBill'

        ]);
        $this->middleware('permission:Return The Purchase Item', ['only' => 'returnPurchaseOrder']);
        $this->middleware('permission:View Receive Purchased Stock', ['only' => 'updatePurchaseOrderReceivedQuantity']);

        $this->warehousePurchaseOrderService = $warehousePurchaseOrderService;
        $this->warehouseService = $warehouseService;
        $this->vendorService = $vendorService;
        $this->categoryService = $categoryService;
        $this->brandService = $brandService;
        $this->warehouseOrderBillService = $warehouseOrderBillService;
        $this->warehouseproductmaster=$warehouseproductmaster;

    }

    public function index(Request $request)
    {
        try{

            $filterParameters = [
                'vendor_code' => $request->get('vendor'),
                'warehouse_code' =>getAuthWarehouseCode(),
                'status' => $request->get('status'),
                'order_date_from' => $request->get('order_date_from'),
                'order_date_to' => $request->get('order_date_to'),
            ];

            $with=[
                'vendor'
            ];
            $statuses=WarehousePurchaseOrder::STATUSES;
            $vendors = $this->vendorService->getAllActiveVendors();
            $purchaseOrders =WarehousePurchaseOrderFilter::filterPaginatedWarehousePurchaseOrders($filterParameters,10,$with);
            return view($this->loadViewData($this->module.$this->view.'index'),compact('purchaseOrders',
                'statuses','vendors','filterParameters'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());

        }

    }

    public function generateWarehousePurchaseOrderBill(Request $request,$warehouseOrderCode)
    {

        try {
            $requestAction = $request->action;
            $warehouseCode = getAuthWarehouseCode();
            $warehousePurchaseOrder = $this->warehousePurchaseOrderService->findOrFailPurchaseOrderByWarehouseCodeWith($warehouseCode,
                $warehouseOrderCode, ['vendor','warehouse','purchaseOrderDetails']);

            return $this->warehouseOrderBillService->generateWarehouseOrderBillPdf($warehousePurchaseOrder,
                $this->module . $this->view . 'purchase-order-bill', $requestAction);
            // $pdf = PDF::loadView($this->module . $this->view . 'store_order_pdf');
            // return $pdf->download('allpasal_store_order.pdf');
            //  return view($this->module . $this->view . 'store_order_pdf');
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }


    public function create()
    {
        //$warehouses = $this->warehouseService->getAllWarehouses();
        $vendors = $this->vendorService->getAllActiveVendors();
        $categories = $this->categoryService->getCategoryMaster(['category_code','category_name']);
        $brands = $this->brandService->getAllBrands();

        //return response()->json($categories);
        return view($this->loadViewData($this->module.$this->view.'create'),compact( 'vendors','categories','brands'));
    }

    public function show($warehouseOrderCode)
    {
        try{
            $warehouseCode = getAuthWarehouseCode();
            $warehousePurchaseOrder = $this->warehousePurchaseOrderService->findOrFailPurchaseOrderByWarehouseCodeWith($warehouseCode,
                $warehouseOrderCode, ['vendor','warehouse','purchaseOrderDetails']);
            //$purchaseOrderDetails = $warehousePurchaseOrder->purchaseOrderDetails;
            $purchaseOrderDetails= $this->warehousePurchaseOrderService->getWarehousePurchaseOrderDetails($warehouseOrderCode);
            $purchaseReturnReasonTypes = WarehousePurchaseReturn::REASON_TYPES;

            return view($this->loadViewData($this->module.$this->view.'show'),compact('warehousePurchaseOrder',
                'purchaseOrderDetails','purchaseReturnReasonTypes'));
        }catch(Exception $exception){
            return redirect()->route('warehouse.warehouse-purchase-orders.index')->with('danger', $exception->getMessage());
        }
    }

    public function store(WarehousePurchaseOrderRequest $warehousePurchaseOrderRequest){

        try{

            $validatedPurchaseOrder = $warehousePurchaseOrderRequest->validated();
            $purchaseOrder =  $this->warehousePurchaseOrderService->newStoreWarehousePurchaseOrder($validatedPurchaseOrder);
            if($purchaseOrder->getOrderStatus() == 'sent'){
                $warehousePurchaseOrderRequest->session()->flash('success', 'Order placed successfully');
                return sendSuccessResponse('Order placed successfully');
            }else{
                $warehousePurchaseOrderRequest->session()->flash('success', 'Order Saved As Draft Successfully');
                return sendSuccessResponse('Order Saved As Draft Successfully');
            }
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function updatePurchaseOrderReceivedQuantity(WarehousePurchaseOrderReceivedQuantityUpdateRequest $request,
                                                        $warehouseOrderCode){
        try{
            $validatedRequest = $request->validated();

            $this->warehousePurchaseOrderService->newUpdateWarehousePurchaseOrderReceivedQuantity($validatedRequest,$warehouseOrderCode);

            return redirect()->back()->with('success','Order received successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function edit($warehouseOrderCode){
        try{
            $warehouseCode = getAuthWarehouseCode();
            $with=[
                'vendor','warehouse',
                'purchaseOrderDetails',
                'purchaseOrderDetails.product',
                'purchaseOrderDetails.product.productVariants',
                'purchaseOrderDetails.productVariant'
            ];
            $warehousePurchaseOrder = $this->warehousePurchaseOrderService->findOrFailPurchaseOrderByWarehouseCodeWith($warehouseCode,
                $warehouseOrderCode, $with);
            if($warehousePurchaseOrder->getOrderStatus() != 'draft'){
                throw new Exception('Edit action available only for draft orders');
            }
            $purchaseOrderDetails = $warehousePurchaseOrder->purchaseOrderDetails;

           // dd($purchaseOrderDetails);
            $vendors = $this->vendorService->getAllActiveVendors();
            $categories = $this->categoryService->getCategoryMaster(['category_code','category_name']);
            $brands = $this->brandService->getAllBrands();

            return view($this->loadViewData($this->module.$this->view.'edit'),compact('warehousePurchaseOrder',
                'purchaseOrderDetails','vendors','categories','brands'));

        }catch(Exception $exception){
            return redirect()->route($this->base_route.'index')->with('danger', $exception->getMessage());
        }
    }

    public function update(){

    }

    public function returnPurchaseOrder(WarehousePurchaseOrderReturnRequest $request,$orderDetailCode){

        try{
            $validatedData = $request->validated();

            $this->warehousePurchaseOrderService->returnWarehousePurchaseOrder($validatedData,$orderDetailCode);
            return redirect()->back()->with('success','Purchase order return proceeded successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

    public function destroy(){

    }
    public function warehousePurchaseOrderList(Request $request)
    {
        try{

            // $filterParameters = [
            //     'vendor_code' =>  $request->get('vendor_code'),
            //     'product_name' =>  $request->get('product_name'),

            // ];

            // $with=[
            //     'package.packageType',
            //     'vendor',
            //     'brand',
            //     'category',
            //     'priceList'

            // ];

//            return response()->json(WarehouseProductMaster::select('*')->with(['product' => function($query){
//                return $query->select('product_name','product_code');
//            }])->groupBy('product_code')->get());
           $productcode=$this->warehouseproductmaster->getProductCode();
            $products=$this->warehouseproductmaster->getProduct($productcode);
//           dd($products);
            //$products = $this->productService->filterProductByVendor($request->filter_by);
            // $vendors = $this->vendorService->getAllVendors();
            return view($this->loadViewData($this->module.$this->view.'wh-purchase-order-list'),compact('products'));
        }catch (Exception $exception){
            return redirect()->route('warehouse.dashboard')->with('danger',$exception->getMessage());
        }


    }


}
