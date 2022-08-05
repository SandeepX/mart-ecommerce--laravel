<?php

namespace App\Modules\SystemSetting\Controllers\Web\Admin\GeneralSetting;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Bank\Services\BankService;
use App\Modules\SystemSetting\Requests\GeneralSettingRequest;
use App\Modules\SystemSetting\Services\GeneralSetting\GeneralSettingService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralSettingController extends BaseController
{
    public $title = 'General Setting';
    public $base_route = 'admin.general-settings';
    public $sub_icon = 'file';
    public $module = 'SystemSetting::';
    public $view = 'admin.general-setting.';

    private $generalSettingService;
    private $bankService;
    public function __construct(GeneralSettingService $generalSettingService,BankService $bankService)
    {
        $this->middleware('permission:View General Setting', ['only' => ['show']]);
        $this->middleware('permission:Update General Setting', ['only' => ['edit','store']]);

        $this->generalSettingService = $generalSettingService;
        $this->bankService = $bankService;
    }

    public function edit(){
        $generalSetting = $this->generalSettingService->getGeneralSetting();
        $banks = $this->bankService->getAllBanks();
        if($generalSetting){
            return view(Parent::loadViewData($this->module.$this->view.'edit'),
                compact('generalSetting','banks'));
        }
        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('banks'));
    }

    public function show()
    {
        $generalSetting = $this->generalSettingService->getGeneralSetting();
        return view(Parent::loadViewData($this->module.$this->view.'show'), compact('generalSetting'));
    }

    public function store(GeneralSettingRequest $generalSettingRequest)
    {
        try{
            $validatedGeneralSetting = $generalSettingRequest->validated();
            $this->generalSettingService->storeGeneralSetting($validatedGeneralSetting);
            return redirect()->back()->with('success', 'General Setting Updated Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }
}
