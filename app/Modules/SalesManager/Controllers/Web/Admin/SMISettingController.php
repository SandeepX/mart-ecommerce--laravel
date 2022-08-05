<?php


namespace App\Modules\SalesManager\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\SalesManager\Requests\SMISettingsRequest;
use App\Modules\SalesManager\Services\ManagerSMISettingsService;
use Exception;

class SMISettingController extends BaseController
{
    public $title = 'Manager SMI Setting';
    public $base_route = 'admin.manager-smi-setting';
    public $sub_icon = 'file';
    public $module = 'SalesManager::';
    private $view = 'admin.manager-smi-settings.';

    private $managerSMISettingsService;

    public function __construct(ManagerSMISettingsService $managerSMISettingsService){
        $this->managerSMISettingsService = $managerSMISettingsService;
    }

    public function index()
    {
        try{
            $managerSMISetting = $this->managerSMISettingsService->getAllManagerSMISetting();
            return view(Parent::loadViewData($this->module . $this->view . 'index'),
                compact('managerSMISetting') );
        }catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function show($msmi_setting_code)
    {
        try{
            $managerSMISetting = $this->managerSMISettingsService->getSMISettingByCode($msmi_setting_code);
            return view(Parent::loadViewData($this->module . $this->view . 'show'),
                compact('managerSMISetting') );
        }catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function create()
    {
        try{
            return view(Parent::loadViewData($this->module . $this->view . 'create'));
        }catch(\Exception $e){
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    public function store(SMISettingsRequest $request)
    {
        try{
            $validatedData = $request->validated();
            $this->managerSMISettingsService->storeSMISetting($validatedData);
            return redirect()->back()->with('success', $this->title . ':  Created Successfully');

        }catch(\Exception $e){
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    public function edit($msmi_setting_code)
    {
        try{
            $smiSetting = $this->managerSMISettingsService->getSMISettingByCode($msmi_setting_code);
            return view(Parent::loadViewData($this->module . $this->view . 'edit'),
                compact('smiSetting') );
        }catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function update(SMISettingsRequest $request,$msmi_setting_code)
    {
        $validatedData = $request->validated();
        try{
            $managerSMISetting =  $this->managerSMISettingsService->updateManagerSMISetting($validatedData,$msmi_setting_code);
            return redirect()->back()->with('success', $this->title . ':  updated Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }


    }

    public function destroy($MSMICode)
    {
        try{
            $managerSMISetting =  $this->managerSMISettingsService->deleteMSMISetting($MSMICode);

            return redirect()->back()->with('success', ' Manager SMI Setting Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

}
