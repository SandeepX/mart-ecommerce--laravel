<?php


namespace App\Modules\User\Controllers\Web\Warehouse;


use App\Modules\AlpasalWarehouse\Services\UserWarehouseService;
use App\Modules\Application\Controllers\BaseController;

use App\Modules\RolePermission\Services\RoleService;
use App\Modules\User\Requests\Warehouse\WarehouseUserCreateRequest;
use App\Modules\User\Requests\Warehouse\WarehouseUserUpdateRequest;
use App\Modules\User\Services\UserService;
use Exception;

class WarehouseUserController extends BaseController
{
    public $title = 'Warehouse User';
    public $base_route = 'warehouse.warehouse-users.';
    public $sub_icon = 'file';
    public $module = 'User::';

    private $view='warehouse.warehouse-user.';

    private $roleService,$userService,$userWarehouseService;

    public function __construct(
        RoleService $roleService,
        UserService $userService,
        UserWarehouseService $userWarehouseService)
    {
        $this->middleware('permission:View List Of Wh Users', ['only' => ['index']]);
        $this->middleware('permission:Create WH User', ['only' => ['create','store']]);
        $this->middleware('permission:Change WH User Status', ['only' => ['toggleUserStatus']]);
        $this->middleware('permission:Update WH User', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete WH User', ['only' => ['destroy']]);

        $this->roleService = $roleService;
        $this->userService = $userService;
        $this->userWarehouseService = $userWarehouseService;
    }

    public function index(){

        // dd(WarehouseQueryHelper::doesUserBelongsToWarehouse(getAuthUserCode(),'AW3'));
        try{
            $users = $this->userService->getUsersOfOwnWarehouse();
            $authUserCode = getAuthUserCode();
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('users','authUserCode'));
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage())->withInput();
        }

    }

    public function create(){
        $roles = $this->roleService->getWarehouseTypeRoles();
        return view(Parent::loadViewData($this->module.$this->view.'create'),compact('roles'));
    }

    public function store(WarehouseUserCreateRequest $request)
    {
        try{
            $validated = $request->validated();
            $user =  $this->userService->storeWarehouseUserWithRole($validated);

        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
        return redirect()->back()->with('success', $this->title . ': '. $user->name .' created successfully');
    }

    public function edit($userCode)
    {
        try{
            $authWarehouseCode= getAuthWarehouseCode();
            $user = $this->userWarehouseService->findOrFailUserByWarehouseCode($authWarehouseCode,$userCode,['roles']);
            $userRolesId = $user->roles()->pluck('id')->toArray();
            $roles = $this->roleService->getWarehouseTypeRoles();
        }catch (Exception $ex){
            return redirect()->route('warehouse.dashboard')->with('danger',$ex->getMessage());
        }

        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('user','roles','userRolesId'));
    }

    public function update(WarehouseUserUpdateRequest $request,$userCode)
    {
        try{
            $validated = $request->validated();
            $user = $this->userWarehouseService->updateWarehouseUserWithRole($validated, $userCode);
            return redirect()->back()->with('success', $this->title . ': '. $user->name .' updated successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function destroy($userCode)
    {
        try{
            $user = $this->userWarehouseService->deleteWarehouseUser($userCode);
            return redirect()->back()->with('success', $this->title . ': '. $user->name .' trashed successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function toggleUserStatus($userCode){
        try{
            $user = $this->userWarehouseService->toggleWarehouseUserStatus($userCode);
            return redirect()->back()->with('success', $this->title . ': '. $user->name .' status updated successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
