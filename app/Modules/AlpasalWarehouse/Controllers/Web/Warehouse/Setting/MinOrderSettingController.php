<?php

namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\Setting;

use App\Modules\AlpasalWarehouse\Requests\Setting\MinOrderSettingCreateRequest;
use App\Modules\AlpasalWarehouse\Requests\Setting\MinOrderSettingUpdateRequest;
use App\Modules\AlpasalWarehouse\Services\Setting\MinOrderSettingService;
use App\Modules\Application\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;

class MinOrderSettingController extends BaseController
{

    public $title = 'Warehouse Minimun Order Settings';
    public $base_route = 'warehouse.min-order-settings';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';
    private $view='warehouse.settings.min-order-settings';


    private $minOrderSettingService;

    public function __construct(MinOrderSettingService $minOrderSettingService)
    {
        $this->minOrderSettingService= $minOrderSettingService;
    }

    public function index()
    {
        $warehouseCode = getAuthWarehouseCode();
        $minOrderSettings = $this->minOrderSettingService->getAllMinOrderSettings($warehouseCode);

        return view($this->loadViewData($this->module.$this->view.'.index'),compact('minOrderSettings'));
    }



    public function store(MinOrderSettingCreateRequest $request)
    {
        try{
            $validated = $request->validated();
            $this->minOrderSettingService->storeMinOrderSettings($validated);
            return redirect()->back()->with('success', $this->title .'Min Order Setting created successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }


    public function edit($warehouseMinOrderAmountSettingCode)
    {
        try{
            $minOrderSetting = $this->minOrderSettingService->findOrFailBySettingMinOrderAmountCode($warehouseMinOrderAmountSettingCode);

            return view($this->loadViewData($this->module.$this->view.'.edit'),compact('minOrderSetting'));
        }
        catch(Exception $exception){
            return redirect()->route($this->base_route.'.index')->with('danger', $exception->getMessage());
        }
    }


    public function update(MinOrderSettingUpdateRequest $request,$warehouseMinOrderAmountSettingCode)
    {
        try{
            $validatedData= $request->validated();
            $minOrderSetting = $this->minOrderSettingService->updateMinOrderSettings($validatedData,$warehouseMinOrderAmountSettingCode);
            return redirect()->back()->with('success', $this->title .' updated successfully');
        }catch (Exception $exception){
            return redirect()->back()
                ->with('danger', $exception->getMessage())
                ->withInput();
        }
    }


    public function destroy($warehouseMinOrderAmountSettingCode)
    {
        try{
            $this->minOrderSettingService->deleteWarehouseMinOrderSetting($warehouseMinOrderAmountSettingCode);
            return redirect()->back()->with('success', $this->title .' deleted successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public  function changeMinOrderStatus($warehouseMinOrderAmountSettingCode)
{
    try{
        $this->minOrderSettingService->changeMinOrderStatus($warehouseMinOrderAmountSettingCode);
        return redirect()->back()->with('success', $this->title .' status updated successfully');
    }catch (Exception $exception){
        return redirect()->back()->with('danger', $exception->getMessage());
    }
}
}
