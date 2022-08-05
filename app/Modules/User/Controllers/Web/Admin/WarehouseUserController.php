<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/19/2020
 * Time: 10:47 AM
 */

namespace App\Modules\User\Controllers\Web\Admin;


use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Application\Controllers\BaseController;

use App\Modules\User\Jobs\SendMailJob;
use App\Modules\User\Mails\PasswordChangedEmail;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\User\Requests\WarehouseAdminPasswordUpdateRequest;
use Exception;

use DB;

class WarehouseUserController extends BaseController
{

    public $title = 'Warehouse User';
    public $base_route = 'admin.warehouse-users.';
    public $sub_icon = 'file';
    public $module = 'User::';

    private $view='admin.warehouse-user.';

    private $warehouseService;
    public function __construct(WarehouseService $warehouseService)
    {
        $this->middleware('permission:Change WH Admin Password AdminSide',
            ['only' => ['editWarehouseAdminPassword','updateWarehouseAdminPassword']]);

        $this->warehouseService = $warehouseService;
    }

    public function editWarehouseAdminPassword($warehouseCode){
        try{
            $warehouse = $this->warehouseService->getWarehouseUser($warehouseCode);
            return view(Parent::loadViewData($this->module.$this->view.'warehouse-password'),compact('warehouse'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function updateWarehouseAdminPassword(WarehouseAdminPasswordUpdateRequest $request,$userCode)
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
                'user_type' => 'Warehouse Admin',
                'login_link' => config('site_urls.ecommerce_site')."/warehouse-login"
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
