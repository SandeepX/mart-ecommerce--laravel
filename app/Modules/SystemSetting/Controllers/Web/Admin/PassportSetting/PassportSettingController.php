<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/7/2020
 * Time: 3:04 PM
 */

namespace App\Modules\SystemSetting\Controllers\Web\Admin\PassportSetting;


use App\Modules\Application\Controllers\BaseController;

use App\Modules\SystemSetting\Requests\PassportSettingRequest;
use App\Modules\SystemSetting\Services\PassportSetting\EnvPassportSettingService;
use Exception;

class PassportSettingController extends BaseController
{

    public $title = 'Passport Setting';
    public $base_route = 'admin.passport-settings';
    public $sub_icon = 'file';
    public $module = 'SystemSetting::';
    public $view = 'admin.passport-setting.';

    private $passportSettingService;

    public function __construct(EnvPassportSettingService $envPassportSettingService){

        $this->middleware('permission:View Passport Setting', ['only' => ['show']]);
        $this->middleware('permission:Update Passport Setting', ['only' => ['edit','update']]);

        $this->passportSettingService = $envPassportSettingService;
    }


    public function edit(){
        try {

            //dd(config('services.passport'));
            //dd(config( EnvMailSetting::ENV_MAIL_KEYS['MAIL_USERNAME']));
            $passportSetting = $this->passportSettingService->getPassportSetting();
            return view(Parent::loadViewData($this->module . $this->view . 'edit'), compact('passportSetting'));
        } catch (Exception $exception) {
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }


    public function update(PassportSettingRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $this->passportSettingService->updatePassportSetting($validatedData);
            return redirect()->back()->with('success', $this->title . ' updated successfully');
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}