<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/4/2020
 * Time: 1:32 PM
 */

namespace App\Modules\SystemSetting\Controllers\Web\Admin\IpAccess;


use App\Modules\Application\Controllers\BaseController;

use App\Modules\SystemSetting\Helpers\IpAccessSettingHelper;
use App\Modules\SystemSetting\Models\IpAccessSetting;
use App\Modules\SystemSetting\Requests\IpAccessStoreRequest;
use App\Modules\SystemSetting\Requests\IpAccessUpdateRequest;
use App\Modules\SystemSetting\Services\IpAccessSetting\IpAccessSettingService;
use Exception;
use Illuminate\Http\Request;

class IpAccessSettingController extends BaseController
{

    public $title = 'Ip Access Setting';
    public $base_route = 'admin.ip-access-settings.';
    public $sub_icon = 'file';
    public $module = 'SystemSetting::';
    public $view = 'admin.ip-access-setting.';

    private $ipAccessSettingService;

    public function __construct(IpAccessSettingService $ipAccessSettingService)
    {
        $this->middleware('permission:View Ip Access List', ['only' => ['index']]);
        $this->middleware('permission:Create Ip Access', ['only' => ['create','store']]);
        $this->middleware('permission:Show Ip Access', ['only' => ['show']]);
        $this->middleware('permission:Update Ip Access', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Ip Access', ['only' => ['destroy']]);

        $this->ipAccessSettingService= $ipAccessSettingService;
    }

    public function index(Request $request){

        try{

            $filterParameters = [
                'ip_name' => $request->get('ip_name'),
                'ip_address' => $request->get('ip_address'),
                'allowed' => $request->get('allowed'),
            ];


            //$ipAddresses = $this->ipAccessSettingService->getAllIpAccesses();
            $ipAddresses = IpAccessSettingHelper::filterPaginatedIpAddresses($filterParameters,IpAccessSetting::RECORDS_PER_PAGE);
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('ipAddresses','filterParameters'));

        }catch (Exception $e){
            return redirect()->route('admin.dashboard')->with('danger',$e->getMessage());
        }

    }

    public function create(){
        try{
            return view(Parent::loadViewData($this->module.$this->view.'create'));
        }catch (Exception $e){
            return redirect()->route($this->base_route.'index')->with('danger', $e->getMessage());
        }
    }

    public function store(IpAccessStoreRequest $request){
        try{
            $validated = $request->validated();
            $this->ipAccessSettingService->saveIpAccessSetting($validated);
            return redirect()->back()->with('success', $this->title .' created successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function edit($ipAccessCode)
    {
        try{
            $ipSetting = $this->ipAccessSettingService->findOrFailIpAccessSettingByCode($ipAccessCode);
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('ipSetting'));

        }catch (Exception $ex){
            return redirect()->route($this->base_route.'index')->with('danger',$ex->getMessage());
        }
    }

    public function update(IpAccessUpdateRequest $request,$ipAccessCode)
    {
        try{
            $validated = $request->validated();
            $this->ipAccessSettingService->updateIpAccessSetting($validated,$ipAccessCode);
            return redirect()->back()->with('success', $this->title .' updated successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }

    }

    public function destroy($ipAccessCode)
    {
        try{
            $ipAccessSetting = $this->ipAccessSettingService->deleteIpAccessSetting($ipAccessCode);
            return redirect()->back()->with('success', $this->title . ': '. $ipAccessSetting->ip_access_code .' trashed successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}