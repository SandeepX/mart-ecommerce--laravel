<?php

namespace App\Modules\SystemSetting\Controllers\Web\Admin\StoreForceLogOut;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\SystemSetting\Requests\ForceStoreUserLogoutRequest;
use App\Modules\SystemSetting\Services\ForceStoreLogout\ForceStoreLogOutService;
use Exception;
use Illuminate\Http\Request;


class ForceStoreLogoutController extends BaseController
{
    public $title = 'Store Users Force logout';
    public $base_route = 'admin.force-logout-store';
    public $sub_icon = 'file';
    public $module = 'SystemSetting::';
    public $view = 'admin.store-force-logout.';

    private $forceStoreLogoutService;

    public function __construct(ForceStoreLogOutService $forceStoreLogoutService)
    {
        $this->forceStoreLogoutService = $forceStoreLogoutService;
    }

    public function index()
    {
        try{
            $getAllStores = $this->forceStoreLogoutService->getAllStores();
            return view(Parent::loadViewData($this->module . $this->view . 'index'),compact('getAllStores'));
        }catch (Exception $exception) {
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }

    public function forceStoreAllUsersLogout(ForceStoreUserLogoutRequest $request)
    {
        try{
            $validatedData  = $request->validated();
            $this->forceStoreLogoutService->forceStoreLogout($validatedData);
            return redirect()->back()->with('success', 'Selected store users logout successfull');

        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
