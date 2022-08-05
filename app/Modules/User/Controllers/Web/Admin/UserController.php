<?php

namespace App\Modules\User\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\RolePermission\Services\RoleService;
use App\Modules\Types\Services\UserTypeService;
use App\Modules\User\Helpers\UserFilter;
use App\Modules\User\Jobs\SendMailJob;
use App\Modules\User\Mails\PasswordChangedEmail;
use App\Modules\User\Models\User;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\User\Requests\UserCreateRequest;
use App\Modules\User\Requests\UserPasswordUpdateRequest;
use App\Modules\User\Requests\UserUpdateRequest;
use App\Modules\User\Services\UserService;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
    public $title = 'User';
    public $base_route = 'admin.users';
    public $sub_icon = 'file';
    public $module = 'User::';

    private $view;
    private $userService,$roleService;
    private $userTypeService;


    public function __construct(UserService $userService,RoleService $roleService,UserTypeService $userTypeService)
    {
        $this->middleware('permission:View Admin List', ['only' => ['index']]);
        $this->middleware('permission:Create Admin', ['only' => ['create','store']]);
        $this->middleware('permission:Show Admin', ['only' => ['show']]);
        $this->middleware('permission:Update Admin', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Admin', ['only' => ['destroy']]);

        $this->view = 'admin.';
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->userTypeService = $userTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try{

            $filterParameters = [
//                'user_type' => ['admin','super-admin'],
                 'user_type' => $request->get('user_type'),
                'user_name' => $request->get('user_name'),
                'email' => $request->get('email'),
            ];

            $with =[
                'userType',
                'latestUserAccountLog'
            ];
            $userTypes = $this->userTypeService->getAllUserTypes();
           // $users = $this->userService->getAdminTypeUsers();
            $users = UserFilter::filterPaginatedUsers($filterParameters,User::RECORDS_PER_PAGE,$with);

            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('users','filterParameters','userTypes'));

        }catch (Exception $e){
            return redirect()->route('admin.dashboard')->with('danger',$e->getMessage());
        }

    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $adminTypeRoles = $this->roleService->getGeneralAdminTypeRoles();
        return view(Parent::loadViewData($this->module.$this->view.'create'),compact('adminTypeRoles'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $validated = $request->validated();
        try{
            $user =  $this->userService->storeAdminWithRole($validated);

        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
        return redirect()->back()->with('success', $this->title . ': '. $user->name .' created successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($userCode)
    {
        try{
            $user = $this->userService->findOrFailUserByCode($userCode);
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', 'No Such Resource Found');
        }
        return view(Parent::loadViewData($this->module.$this->view.'show'),compact('user'));

    }


    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($userCode)
    {
        try{
            $user = $this->userService->findOrFailUserByCodeWith($userCode,['roles']);
            if(!$user->isAdminUser()){
                 throw new Exception('Cannot Edit Other than Super admin or Admin');
            }
            $userRolesId = $user->roles()->pluck('id')->toArray();
            $adminTypeRoles = $this->roleService->getGeneralAdminTypeRoles();
        }catch (\Exception $ex){
            return redirect()->back()->with('danger',$ex->getMessage());
        }

        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('user','adminTypeRoles','userRolesId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request,$userCode)
    {
        try{
            $validated = $request->validated();
            $userDetail = $this->userService->findOrFailUserByCode($userCode);
            if(!$userDetail->isAdminUser()){
                throw new Exception('Cannot Update Other than Super admin or Admin');
            }
            $user = $this->userService->updateUserWithRole($validated, $userCode);
            return redirect()->back()->with('success', $this->title . ': '. $user->name .' Updated Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($userCode)
    {
        try{
            $userDetail = $this->userService->findOrFailUserByCode($userCode);
            if(!$userDetail->isAdminUser()){
                throw new Exception('Cannot Delete Other than Super admin or Admin');
            }
            $user = $this->userService->deleteUser($userCode);
            return redirect()->back()->with('success', $this->title . ': '. $user->name .' trashed successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
    public function editAdminPassword(Request $request,$userCode){
        try{
            $user = $this->userService->findOrFailUserByCodeWith($userCode,['roles']);

            if(!$user->isAdminUser()){
                throw new Exception('Cannot Edit Admin Password Other than Super admin or Admin');
            }
            if ($request->ajax()) {
                return view('User::admin.common.user-password-edit',
                    compact('user'))->render();
            }
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
    public function updateAdminPassword(UserPasswordUpdateRequest $request,$userCode)
    {
        try{
            $validated = $request->validated();
            $user= $this->userService->findOrFailUserByCode($userCode);

            DB::beginTransaction();
            $this->userService->updateAdminPassword($user,$validated['password']);

            // dispatching password changed mail
            $data = [
                'name' => $user['name'],
                'login_email' => $user['login_email'],
                'login_password' => $validated['password'],
                'user_type' => 'Admin',
                'login_link' => config('site_urls.ecommerce_site')."/admin-login"
            ];
            SendMailJob::dispatch($user['login_email'],new PasswordChangedEmail($data));

            DB::commit();
            return redirect()->back()->with('success', $this->title . ': '. $user->name .' password updated successfully');
        }catch (Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function toggleActiveStatus($userCode){
        try{
            $this->userService->toggleActiveStatus($userCode);
            return redirect()->back()->with('success', ' Account Status changed  Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }
}
