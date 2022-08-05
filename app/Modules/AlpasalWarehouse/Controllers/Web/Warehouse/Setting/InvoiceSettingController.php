<?php

namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\Setting;

use App\Modules\AlpasalWarehouse\Models\Setting\InvoiceSetting;
use App\Modules\AlpasalWarehouse\Requests\Setting\InvoiceSettingCreateRequest;
use App\Modules\AlpasalWarehouse\Services\Setting\InvoiceSettingService;
use App\Modules\Application\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;

class InvoiceSettingController extends BaseController
{

    public $title = 'Warehouse Settings';
    public $base_route = 'warehouse.settings';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';
    private $view='warehouse.settings';


    private $invoiceSettingService;

    public function __construct(InvoiceSettingService $invoiceSettingService)
    {
        $this->middleware('permission:View Invoice Setting Lists', ['only' => [
            'settings',
            'index',
            'show'
        ]]);
        $this->middleware('permission:Create Invoice Setting', ['only' => [
            'create',
            'store'
        ]]);
        $this->middleware('permission:Update Invoice Setting', ['only' => [
            'edit',
            'update'
        ]]);
        $this->middleware('permission:Delete Invoice Setting', ['only' => 'destroy']);
        $this->invoiceSettingService= $invoiceSettingService;
    }

    public function settings(){

        return view($this->loadViewData($this->module.$this->view.'.settings'));
    }

    public function index()
    {
        $invoicesettings = $this->invoiceSettingService->getAllInvoiceSettings();
      return view($this->loadViewData($this->module.$this->view.'.invoice-settings.index'),compact('invoicesettings'));
    }


    public function create()
    {
        return view($this->loadViewData($this->module.$this->view.'.invoice-settings.create'));
    }


    public function store(InvoiceSettingCreateRequest $request)
    {
        try{
            $validated = $request->validated();
            $this->invoiceSettingService->storeInvoiceSettings($validated);
            return redirect()->back()->with('success', $this->title .'Invoice created successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }


    public function show()
    {
        return view('AlpasalWarehouse::show');
    }


    public function edit($settingWarehouseInvoiceCode)
    {
       try{
          $invoiceSettings = $this->invoiceSettingService->findOrFailBySettingInvoiceCode($settingWarehouseInvoiceCode);

          return view($this->loadViewData($this->module.$this->view.'.invoice-settings.edit'),compact('invoiceSettings'));
       }
      catch(Exception $exception){
          return redirect()->route($this->base_route.'.invoice.index')->with('danger', $exception->getMessage());
      }
    }


    public function update(InvoiceSettingCreateRequest $request,$settingWarehouseInvoiceCode)
    {
        try{
            $validatedData= $request->validated();
            $warehousePreOrder = $this->invoiceSettingService->updateInvoiveSettings($validatedData,$settingWarehouseInvoiceCode);
            return redirect()->back()->with('success', $this->title .' updated successfully');
        }catch (Exception $exception){
            return redirect()->back()
                ->with('danger', $exception->getMessage())
                ->withInput();
        }
    }


    public function destroy($settingWarehouseInvoiceCode)
    {
        try{
            $this->invoiceSettingService->deleteWarehouseSettingInvoice($settingWarehouseInvoiceCode);
            return redirect()->back()->with('success', $this->title .'deleted successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
