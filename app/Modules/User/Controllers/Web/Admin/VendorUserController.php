<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/5/2020
 * Time: 2:35 PM
 */

namespace App\Modules\User\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\RolePermission\Services\RoleService;
use App\Modules\User\Jobs\SendMailJob;
use App\Modules\User\Mails\PasswordChangedEmail;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\User\Requests\TempVendorAdminPasswordUpdateRequest;
use App\Modules\User\Requests\VendorUserUpdateByAdminRequest;
use App\Modules\User\Services\UserService;

use App\Modules\Vendor\Services\VendorService;
use Exception;

use DB;
class VendorUserController extends BaseController
{

    public $title = 'Vendor User';
    public $base_route = 'admin.vendor-users.';
    public $sub_icon = 'file';
    public $module = 'User::';

    private $view='admin.vendor-user.';

    private $userService,$roleService,$vendorService;

    public function __construct(UserService $userService,
                                RoleService $roleService,VendorService $vendorService)
    {
        $this->middleware('permission:View Vendor Admin List', ['only' => ['index']]);
        $this->middleware('permission:Create Vendor Admin', ['only' => ['create','store']]);
        $this->middleware('permission:Show Vendor Admin', ['only' => ['show']]);
        $this->middleware('permission:Update Vendor Admin', ['only' => ['edit','update','editVendorAdminPassword','updateVendorAdminPassword']]);
        $this->middleware('permission:Delete Vendor Admin', ['only' => ['destroy']]);

        $this->userService = $userService;
        $this->roleService= $roleService;
        $this->vendorService= $vendorService;
    }

    public function index(){
        $users = $this->userService->getVendorAdmins();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($userCode)
    {
        try{
            // $user = $this->userService->findOrFailUserByCode($userCode);
            $user = $this->userService->findOrFailUserByCodeWith($userCode,['roles']);
            $userRolesId = $user->roles()->pluck('id')->toArray();
            $vendorTypeRoles = $this->roleService->getVendorTypeRoles();
        }catch (Exception $ex){
            return redirect()->route('admin.dashboard')->with('danger',$ex->getMessage());
        }

        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('user','vendorTypeRoles','userRolesId'));
    }


    public function update(VendorUserUpdateByAdminRequest $request,$userCode)
    {
        try{
            $validated = $request->validated();
            $user = $this->userService->updateUserWithRole($validated, $userCode);
            return redirect()->back()->with('success', $this->title . ': '. $user->name .' updated successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function editVendorAdminPassword($vendorCode){

        try{

            $address = config('mail.from.address');
            $subject = 'Password Changed';
            $name = config('mail.from.name');

           // dd($name);
            $vendor = $this->vendorService->findOrFailVendorByCode($vendorCode);
            $vendorAdmin = $vendor->user;
            return view(Parent::loadViewData($this->module.$this->view.'vendor-password'),compact('vendorAdmin'));
        }catch (Exception $exception){
            return redirect()->route('admin.vendors.')->with('danger', $exception->getMessage());
        }
    }

    public function updateVendorAdminPassword(TempVendorAdminPasswordUpdateRequest $request,$userCode)
    {
        try{
            $validated = $request->validated();
           $userRepo = new UserRepository();
           $user= $userRepo->findOrFailUserByCode($userCode);

           DB::beginTransaction();
           $userRepo->updateUserPassword($user,$validated['password']);

            // dispatching password changed mail
            $data = [
                'name' => $user['name'],
                'login_email' => $user['login_email'],
                'login_password' => $validated['password'],
                'user_type' => 'vendor',
                'login_link' => config('site_urls.ecommerce_site')."/user-login"
            ];
            SendMailJob::dispatch($user['login_email'],new PasswordChangedEmail($data));

            DB::commit();

            return redirect()->back()->with('success', $this->title . ': '. $user->name .' password updated successfully');
        }catch (Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}