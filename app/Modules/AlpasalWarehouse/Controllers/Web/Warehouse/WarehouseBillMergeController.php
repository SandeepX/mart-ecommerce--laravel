<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse;

use App\Exceptions\Custom\NotEnoughProductStockException;
use App\Modules\AlpasalWarehouse\Exports\BillMergePDFExport;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductFilter;
use App\Modules\AlpasalWarehouse\Requests\WarehouseBillMergeRequest;
use App\Modules\AlpasalWarehouse\Requests\WarehouseBillMergeStatusUpdateRequest;
use App\Modules\AlpasalWarehouse\Requests\WarehouseProductChangeStatus;
use App\Modules\AlpasalWarehouse\Requests\WarehouseProductPriceSettingRequest;
use App\Modules\AlpasalWarehouse\Requests\WarehouseWholeProductChangeStatusRequest;
use App\Modules\AlpasalWarehouse\Services\Bill\WarehouseBillMergeService;
use App\Modules\AlpasalWarehouse\Services\WarehouseProductService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Vendor\Services\VendorService;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Modules\AlpasalWarehouse\Models\BillMerge\BillMergeMaster;
use App\Modules\AlpasalWarehouse\Models\BillMerge\BillMergeProduct;

use Exception;
class WarehouseBillMergeController extends  BaseController
{

    public $title = 'Alpasal Warehouse Bill Merge';
    public $base_route = 'warehouse.bill-merge.';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view='warehouse.bill-merge.';

    private $warehouseBillMergeService;

    public function __construct(WarehouseBillMergeService $warehouseBillMergeService)
    {
        $this->middleware('permission:View Bill Merge Master List', ['only' => ['index']]);
        $this->middleware('permission:Create Bill Merge', ['only' => ['mergeForm','getMergedBill']]);
        $this->middleware('permission:Show Bill Merge Products', ['only' => ['getProductsByBillMergeMasterCode']]);
        $this->middleware('permission:Update Bill Merge Product Detail', ['only' => ['updateBillMergeProductDetail']]);
        $this->middleware('permission:Update Bill Merge Master Status', ['only' => ['updateStatusOfBillMergeMaster']]);
        $this->middleware('permission:Bill Merge Generate Bill', ['only' => ['generateBill']]);
        $this->middleware('permission:Show Bill Merge Order Details', ['only' => ['getMergeOrderDetailsByMasterCode']]);

        $this->warehouseBillMergeService=$warehouseBillMergeService;
    }
    public function index(Request $request){
        //$user=auth()->user;
       // $user = auth()->user();
        //dd($user);
         // return response()->json($user->getPermissionsViaRoles());
        try{
            $filterParameters = [
                'store_name'=>$request->get('store_name')
            ];
            $warehouseCode = getAuthWarehouseCode();
            $mergedOrders=$this->warehouseBillMergeService->getAllMergedOrders($warehouseCode,$filterParameters);

            return view($this->loadViewData($this->module.$this->view.'index'),
                compact('mergedOrders','filterParameters'));
        }catch (Exception $exception){
            return redirect()->route('warehouse.dashboard')->with('danger',$exception->getMessage());
        }
    }

    public function mergeForm(){
        try{
            $stores=$this->warehouseBillMergeService->getAllStoresOfWarehouse();
            return view($this->loadViewData($this->module.$this->view.'form'),compact('stores'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function getOrder($storeCode)
    {
        try{
            $storeOrders=$this->warehouseBillMergeService-> getAllStoreOrdersOfWarehouse($storeCode);
            $storePreOrders=$this->warehouseBillMergeService->getAllStorePreOrdersOfWarehouse($storeCode);

            return sendSuccessResponse('data found',[
                'storeOrders'=>$storeOrders,
                'storePreOrders'=>$storePreOrders,
            ]);
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function getMergedBill(WarehouseBillMergeRequest $request)
    {
        try{
            $validatedData = $request->validated();
            $this->warehouseBillMergeService->storeBillMergeAllDetails($validatedData);
            return redirect()->back()->with('success','Bill merged successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
    public function getProductsByBillMergeMasterCode($billMergeMasterCode)
    {
        try{
            $with = ['billMergeDetail'];
            $warehouseCode = getAuthWarehouseCode();
            $masterBillMerge = $this->warehouseBillMergeService->findBillMergeMasterByCode($billMergeMasterCode);
            if($masterBillMerge->warehouse_code != $warehouseCode){
                throw new Exception('You are not authorised to view this bill merge products');
            }

            $mergedProducts=$this->warehouseBillMergeService->getProductsByBillMergeMasterCode($billMergeMasterCode,$with);
            return view($this->loadViewData($this->module.$this->view.'product-lists'),compact('mergedProducts','masterBillMerge'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function updateBillMergeProductDetail(Request $request,$billMergeDetailCode,$billMergeProductCode)
    {
        try{
            $validatedData = [
              'quantity'=>$request->quantity,
              'status'=>$request->status
            ];
            $this->warehouseBillMergeService->updateBillMergeProductDetail($billMergeDetailCode,$billMergeProductCode,$validatedData);
            return sendSuccessResponse('Bill Merge Product Updated Successfully',$validatedData);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage());
           // return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function updateStatusOfBillMergeMaster(WarehouseBillMergeStatusUpdateRequest  $request,$billMergeMasterCode)
    {
        try{
           $validatedData = $request->validated();
           $this->warehouseBillMergeService->updateBillMergeStatusByWarehouse($validatedData,$billMergeMasterCode);
            return redirect()->back()->with('success', $this->title .' status updated successfully');
        }catch(Exception $exception){
            if($exception instanceof  NotEnoughProductStockException){
                return redirect()->back()->with('stock_unavailable_items',$exception->getData());
            }
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function generateBill($billMergeMasterCode){

        try{
            $warehouseBillMergeProducts = $this->warehouseBillMergeService->getBillMergeProductsforPdfBill($billMergeMasterCode);

            $orderInfo = $warehouseBillMergeProducts['order_info'];
            $warehouseBillMergeProductsWithChunk= $warehouseBillMergeProducts['bill_merge_products'];

            $pdfExport = new BillMergePDFExport($orderInfo,$warehouseBillMergeProductsWithChunk);

            return $pdfExport->export('download');

        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

    public function getMergeOrderDetailsByMasterCode($billMergeMasterCode){

        try{
            $warehouseCode = getAuthWarehouseCode();
            $billMergeMaster = $this->warehouseBillMergeService->findBillMergeMasterByCode($billMergeMasterCode);
            if($billMergeMaster->warehouse_code != $warehouseCode){
                throw new Exception('You are not authorised to view this bill merge details');
            }
            $mergeDetails = $this->warehouseBillMergeService->getMergeOrderDetailsByMasterCode($billMergeMasterCode);

            return view($this->loadViewData($this->module.$this->view.'merge-details'),compact('mergeDetails'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

}
