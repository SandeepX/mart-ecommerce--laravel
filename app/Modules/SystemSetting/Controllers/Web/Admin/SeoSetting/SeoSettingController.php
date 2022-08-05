<?php

namespace App\Modules\SystemSetting\Controllers\Web\Admin\SeoSetting;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\SystemSetting\Requests\SeoSettingRequest;
use App\Modules\SystemSetting\Services\SeoSetting\SeoSettingService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeoSettingController extends BaseController
{
    public $title = 'Seo Setting';
    public $base_route = 'admin.seo-settings';
    public $sub_icon = 'file';
    public $module = 'SystemSetting::';
    public $view = 'admin.seo-setting.';
    
    private $seoSettingService;
    public function __construct(SeoSettingService $seoSettingService)
    {
        $this->middleware('permission:View Seo Setting', ['only' => ['show']]);
        $this->middleware('permission:Update Seo Setting', ['only' => ['edit','store']]);

        $this->seoSettingService = $seoSettingService;
    }

    public function edit(){
        $seoSetting = $this->seoSettingService->getSeoSetting();
        if($seoSetting){
            return view(Parent::loadViewData($this->module.$this->view.'edit'), compact('seoSetting'));
        }
        return view(Parent::loadViewData($this->module.$this->view.'edit'));
    }

    public function show()
    {
        $seoSetting = $this->seoSettingService->getSeoSetting();
        return view(Parent::loadViewData($this->module.$this->view.'show'), compact('seoSetting'));
    }

    public function store(SeoSettingRequest $seoSettingRequest)
    {
        
        $validatedSeoSetting = $seoSettingRequest->validated();
        DB::beginTransaction();
        try{
            $this->seoSettingService->storeSeoSetting($validatedSeoSetting);
            DB::commit();
            return redirect()->back()->with('success', 'Seo Setting Updated Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }
}