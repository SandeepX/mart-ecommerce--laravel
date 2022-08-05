<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/5/2020
 * Time: 2:17 PM
 */

namespace App\Modules\RolePermission\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;

use App\Modules\RolePermission\Requests\RoleStoreRequest;
use App\Modules\RolePermission\Requests\RoleUpdateRequest;
use App\Modules\RolePermission\Services\PermissionService;
use App\Modules\RolePermission\Services\RoleService;
use Exception;
use Illuminate\Http\Request;

class RoleController extends BaseController
{

    public $title = 'Role';
    public $base_route = 'admin.roles.';
    public $sub_icon = 'file';
    public $module = 'RolePermission::';

    private $view='admin.roles.';

    private $permissionService , $roleService;

    public function __construct(PermissionService $permissionService,RoleService $roleService){

        $this->middleware('permission:View Role List', ['only' => ['index']]);
        $this->middleware('permission:Create Role', ['only' => ['create','store']]);
        $this->middleware('permission:Show Role', ['only' => ['show']]);
        $this->middleware('permission:Update Role', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Role', ['only' => ['destroy']]);

        $this->permissionService = $permissionService;
        $this->roleService = $roleService;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        try{
            $roles =$this->roleService->getAllRoles();
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('roles'));
        }catch(Exception $e){
            return redirect()->back()->with('flash_message_error', $e->getMessage());
        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        try{
            $permissionGroups= $this->permissionService->getCategoryGroupedPermissions();
            $roleUserTypes = $this->roleService->getRoleUserTypes();

            return view(Parent::loadViewData($this->module.$this->view.'create'),compact(
                'permissionGroups','roleUserTypes'));
        }catch (Exception $e){
            return redirect()->route($this->base_route.'index')->with('danger', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(RoleStoreRequest $request)
    {
        try{
            $validated = $request->validated();

            $this->roleService->storeRole($validated);
            return redirect()->back()->with('success', $this->title .' created successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }

    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        try{

            $role = $this->roleService->findOrFailRoleByIdWithPermission($id);
            $rolePermissionsId = $role->permissions->pluck('id')->toArray();
            //$permissionGroups= $this->permissionService->getCategoryGroupedPermissions();
            $permissionGroups= $this->permissionService->getRolewisePermissions($role->for_user_type);

            $roleUserTypes = $this->roleService->getRoleUserTypes();
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('role','rolePermissionsId',
                'permissionGroups','roleUserTypes'));

        }catch (Exception $ex){
            return redirect()->route($this->base_route.'index')->with('danger',$ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(RoleUpdateRequest $request,$id)
    {
        try{
            $validated = $request->validated();
            $role = $this->roleService->updateRole($validated,$id);
            return redirect()->back()->with('success', $this->title .' updated successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try{
            $role = $this->roleService->deleteRole($id);
            return redirect()->back()->with('success', $this->title . ': '. $role->name .' trashed successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function rolesFilter(Request $request){
        try{
            $forUserType = $request->get('for_user_type');

            if(!$forUserType)
            {
                throw new Exception('user type not found');
            }

            $permissionGroups= $this->permissionService->getRolewisePermissions($forUserType);
            //dd($permissionGroups);
            $roleUserTypes = $this->roleService->getWarehouseTypeRoles();


            if ($request->ajax()) {
                return view(Parent::loadViewData($this->module.$this->view.'permission-list'),compact(
                    'permissionGroups','roleUserTypes'))->render();
            }
            return $permissionGroups;
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
