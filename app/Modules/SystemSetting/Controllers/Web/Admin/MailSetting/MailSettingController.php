<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/6/2020
 * Time: 1:22 PM
 */

namespace App\Modules\SystemSetting\Controllers\Web\Admin\MailSetting;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\SystemSetting\Requests\MailSettingRequest;
use App\Modules\SystemSetting\Services\MailSetting\EnvMailSettingService;
use Exception;
use Illuminate\Support\Facades\Config;

class MailSettingController extends BaseController
{
    public $title = 'Mail Setting';
    public $base_route = 'admin.mail-settings';
    public $sub_icon = 'file';
    public $module = 'SystemSetting::';
    public $view = 'admin.mail-setting.';

    private $mailSettingService;

    public function __construct(EnvMailSettingService $envMailSettingService)
    {
        $this->middleware('permission:View Mail Setting', ['only' => ['show']]);
        $this->middleware('permission:Update Mail Setting', ['only' => ['edit','update']]);

        $this->mailSettingService = $envMailSettingService;
    }

    public function edit()
    {
        try {

          // dd(config('mail'));
           //dd(config( EnvMailSetting::ENV_MAIL_KEYS['MAIL_USERNAME']));
            $mailSetting = $this->mailSettingService->getMailSetting();
            $mailDrivers = $this->mailSettingService->getMailDrivers();
            return view(Parent::loadViewData($this->module . $this->view . 'edit'), compact('mailSetting','mailDrivers'));
        } catch (Exception $exception) {
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }

    public function update(MailSettingRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $this->mailSettingService->updateMailSetting($validatedData);
            return redirect()->back()->with('success', $this->title . ' Updated Successfully');
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

}