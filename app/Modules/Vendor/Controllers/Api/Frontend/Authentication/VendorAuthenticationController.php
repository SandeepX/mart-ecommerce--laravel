<?php

namespace App\Modules\Vendor\Controllers\Api\Frontend\Authentication;

use App\Modules\ActivityLog\Helpers\LogActivity;
use App\Modules\ActivityLog\Jobs\LogActivityJob;
use App\Modules\Application\Controllers\BaseAuthenticationController;
use App\Modules\User\Models\User;
use App\Modules\User\Resources\MinimalUserResource;
use App\Modules\User\Services\UserService;
use App\Modules\Vendor\Resources\MinimalVendorResource;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VendorAuthenticationController extends BaseAuthenticationController
{

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function loginVendor(Request $request)
    {
        try {

            $request->validate([
                'username' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::whereHas('userType', function ($query) {
                $query->where('slug', 'vendor');
            })->whereHas('vendor')
                ->with(['userType','vendor'])
                ->where('login_email', $request['username'])
                ->first();

            if (!$user) {
                throw new \Exception('Invalid Email Login Credentials !', 401);
            }
            if($user->vendor->is_active == 0)
            {
                throw new \Exception('Your account has Inactive or suspended , Please contact Allpasal Administrative !', 401);
            }
            if (!$user->validateForPassportPasswordGrant($request->password)) {
                throw new \Exception('Invalid Login Credentials !', 401);
            }

            if(!$user->isActive()){
                throw new \Exception('Your user account has been Deactivated, Please contact administration !', 401);
            }
            if($user->isBanned()){
                throw new \Exception('Your user account has been Banned, PLease contact administration !',401);
            }
            if($user->isSuspended()){
                throw new \Exception('Your user account has been Suspended, Please contact administration !',401);
            }

            //$tokens = $user->createToken('MyToken'.$user->user_code)->accessToken;
            $tokens = $this->getTokenAndRefreshToken($request);

            $this->userService->updateLastLoginDetail($user);
//            LogActivityJob::dispatch(" New login by vendor : ".$user->vendor->vendor_name)->onQueue('low');
            LogActivity::addToLog("New login by vendor",[],$user->user_code);
            return sendSuccessResponse(
                'Authenticated',
                [
                    'user' => new MinimalUserResource($user),
                    'vendor_details' => new MinimalVendorResource($user->vendor),
                    'tokens' => $tokens
                ]
            );


        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                return sendErrorResponse($e->getMessage(), 422, $e->errors());
            }

            return sendErrorResponse($e->getMessage(), 401);
        }


    }
}
