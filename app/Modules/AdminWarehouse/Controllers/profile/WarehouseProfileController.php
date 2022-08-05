<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/23/2020
 * Time: 4:27 PM
 */

namespace App\Modules\AdminWarehouse\Controllers\Profile;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\User\Requests\PasswordUpdateRequest;
use App\Modules\User\Services\UserService;

use Exception;
use Auth;

class WarehouseProfileController extends BaseController
{
    public $title = 'Password';
    public $base_route = 'warehouse.';
    public $sub_icon = 'file';
    public $module = 'AdminWarehouse::';

    private $view='profile.';
    private $userService;

    public function __construct(UserService $userService){

        $this->userService = $userService;
    }

    public function changePassword(){
        return view(Parent::loadViewData($this->module.$this->view.'change-password'));
    }

    public function updatePassword(PasswordUpdateRequest $request){
        try{
            // Retrieve the validated input data...
            $validatedInput = $request->validated();

            $user = Auth::user();

            $this->userService->updateUserPassword($user,$validatedInput); // Retrieve the validated input data...

            return redirect()->route('warehouse.login')
                ->with('success','Your password has been updated! please login again' );

        }catch (Exception $e){

            return redirect()->back()->with('danger', $e->getMessage());
        }
    }
}
