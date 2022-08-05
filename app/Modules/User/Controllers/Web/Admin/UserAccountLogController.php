<?php


namespace App\Modules\User\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\User\Requests\BanedUserRequest;
use App\Modules\User\Requests\SuspendUserRequest;
use App\Modules\User\Services\UserAccountLogService;
use App\Modules\User\Services\UserService;
use Exception;
use Illuminate\Http\Request;

class UserAccountLogController extends BaseController
{
    public $title = 'User Account Log';
    public $base_route = 'admin.user-account-logs';
    public $sub_icon = 'file';
    public $module = 'User::';
    private $view;

    private $userService;
    private $userAccountLogService;


    public function __construct(UserService $userService,UserAccountLogService $userAccountLogService){
           $this->userService = $userService;
           $this->userAccountLogService = $userAccountLogService;
           $this->view = 'admin.account-log.';
    }


    public function storeSuspendUserDetail(SuspendUserRequest $request,$userCode){

        try{
            $validatedData = $request->validated();
            $this->userAccountLogService->storeSuspendUserDetail($userCode,$validatedData);
            return redirect()->back()->with('success','UserCode:'.$userCode.' Suspended successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }

    public function unSuspendUser($userCode){

        try{
            $this->userAccountLogService->unSuspendUser($userCode);
            return redirect()->back()->with('success','UserCode:'.$userCode.' UnSuspended successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }

    public function storeBannedUserDetail(BanedUserRequest $request, $userCode){
        try{
            $validatedData = $request->validated();
            $this->userAccountLogService->storeBannedUserDetail($userCode,$validatedData);
            return redirect()->back()->with('success','UserCode: '.$userCode.' Banned Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function unBanUser($userCode){
        try{
            $this->userAccountLogService->unBanUser($userCode);
            return redirect()->back()->with('success','UserCode:'.$userCode.' UnBanned successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function getUserAccountLogByUserCode($userCode){

          try{
              $user = $this->userService->findOrFailUserByCode($userCode);
              $userAccountLogs = $this->userAccountLogService->getUserAccountLogsByUserCode($userCode);
              return view(Parent::loadViewData($this->module.$this->view.'index'),compact('user','userAccountLogs'));
          }catch(Exception $exception){
              return redirect()->back()->with('danger',$exception->getMessage());
          }

    }


}
