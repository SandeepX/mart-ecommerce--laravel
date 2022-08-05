<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/19/2020
 * Time: 10:47 AM
 */

namespace App\Modules\User\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Services\StoreService;

use App\Modules\User\Jobs\SendMailJob;
use App\Modules\User\Mails\PasswordChangedEmail;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\User\Requests\TempStoreAdminPasswordUpdateRequest;
use Exception;

use DB;

class TempStoreUserController extends BaseController
{

    public $title = 'Store User';
    public $base_route = 'admin.store-users.';
    public $sub_icon = 'file';
    public $module = 'User::';

    private $view='admin.store-user.';

    private $storeService;
    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function editStoreAdminPassword($storeCode){

        try{
            $store = $this->storeService->findOrFailStoreByCode($storeCode);
            $storeAdmin = $store->user;
            return view(Parent::loadViewData($this->module.$this->view.'store-password'),compact('storeAdmin'));
        }catch (Exception $exception){
            return redirect()->route('admin.vendors.')->with('danger', $exception->getMessage());
        }
    }

    public function updateStoreAdminPassword(TempStoreAdminPasswordUpdateRequest $request,$userCode)
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
                'login_link' => config('site_urls.ecommerce_site')."/store-login"
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