<?php

/**
 * Created by PhpStorm.
 * User: Sandeep
 * Date: 10/22/2021
 * Time: 12:27 PM
 */

namespace App\Modules\SupportAdmin\Controllers\profile;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\User\Requests\PasswordUpdateRequest;
use App\Modules\User\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Exception;

class SupportAdminProfileController extends BaseController
{
    public $title = 'Password';
    public $base_route = 'support-admin.';
    public $sub_icon = 'file';
    public $module = 'SupportAdmin::';

    private $view = 'profile.';
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function changePassword()
    {
        return view(Parent::loadViewData($this->module . $this->view . 'change-password'));
    }

    public function updatePassword(PasswordUpdateRequest $request)
    {
        try {
            // Retrieve the validated input data...
            $validatedInput = $request->validated();
            $user = Auth::user();
            $this->userService->updateUserPassword($user, $validatedInput); // Retrieve the validated input data...

            return redirect()->route('support-admin.login')
                ->with('success', 'Your password has been updated! please login again');

        } catch (Exception $e) {
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }
}

