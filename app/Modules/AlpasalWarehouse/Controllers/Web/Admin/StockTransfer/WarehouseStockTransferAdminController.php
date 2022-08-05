<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Admin\StockTransfer;


use App\Modules\AlpasalWarehouse\Requests\StockTransfer\AdminStockTransferRequest;
use App\Modules\AlpasalWarehouse\Services\StockTransfer\AdminStockTransferService;
use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Application\Controllers\BaseController;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehouseStockTransferAdminController extends BaseController
{

    public $title = 'Admin Stock Transfer';
    public $base_route = 'admin.warehouses.stock-transfer';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';
    public $view = 'admin.stock-transfer.';

    private $warehouseService;
    private $adminStockTransferService;

    public function __construct(
        WarehouseService $warehouseService,
        AdminStockTransferService $adminStockTransferService
    ){
        $this->middleware('permission:View Admin Stock Transfer',
            ['only' => ['stockTransferForm']]);
        $this->middleware('permission:Save Admin Stock Transfer',
            ['only' => ['saveTransfer']]);
        $this->warehouseService = $warehouseService;
        $this->adminStockTransferService = $adminStockTransferService;
    }

    public function stockTransferForm(){
        try{
            $warehouses = $this->warehouseService->getAllWarehouses();
            return view(Parent::loadViewData($this->module.$this->view.'create'),compact('warehouses'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function saveTransfer(AdminStockTransferRequest $request){
        try{
           // throw new Exception('Stock Transfer Feature is halted. Please Contact administration!');
            $validated = $request->validated();
            DB::beginTransaction();
            $this->adminStockTransferService->stockTransfer($validated);
            DB::commit();
            return redirect()->back()->with('success','Warehouse stock transfer completed Successfully')->withInput();
        }catch (Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

}
