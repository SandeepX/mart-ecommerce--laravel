<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/8/2020
 * Time: 10:35 AM
 */

namespace App\Modules\SystemSetting\Controllers\Web\Admin\SiteUrlSetting;


use App\Modules\Application\Controllers\BaseController;

use App\Modules\SystemSetting\Requests\UrlSettingRequest;
use App\Modules\SystemSetting\Services\UrlSetting\EnvUrlSettingService;
use Exception;

class SiteUrlSettingController extends BaseController
{

    public $title = 'Site Url Setting';
    public $base_route = 'admin.url-settings';
    public $sub_icon = 'file';
    public $module = 'SystemSetting::';
    public $view = 'admin.url-setting.';

    private $urlSettingService;

    public function __construct(EnvUrlSettingService $envUrlSettingService)
    {
        $this->middleware('permission:View Site Url Setting', ['only' => ['show']]);
        $this->middleware('permission:Update Site Url Setting', ['only' => ['edit','update']]);

        $this->urlSettingService = $envUrlSettingService;
    }

    public function edit(){
        try {
            $urlSetting = $this->urlSettingService->getSiteUrlSetting();
            return view(Parent::loadViewData($this->module . $this->view . 'edit'),compact('urlSetting'));
        } catch (Exception $exception) {
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }

    public function update(UrlSettingRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $this->urlSettingService->updateSiteUrlSetting($validatedData);
            return redirect()->back()->with('success', $this->title . ' updated successfully');
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}