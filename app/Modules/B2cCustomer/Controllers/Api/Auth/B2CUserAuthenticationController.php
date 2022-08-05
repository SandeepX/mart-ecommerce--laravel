<?php


namespace App\Modules\B2cCustomer\Controllers\Api\Auth;

use App\Modules\Application\Controllers\BaseAuthenticationController;

use App\Modules\B2cCustomer\Resources\B2CUserRegistrationStatusResource;
use App\Modules\User\Models\User;
use App\Modules\User\Resources\MinimalUserResource;
use App\Modules\User\Services\UserService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class B2CUserAuthenticationController extends BaseAuthenticationController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function loginB2CUser(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::whereHas('userType', function ($query) {
                $query->where('slug', 'b2c-customer');
            })
                ->with(['userType'])
                ->where('login_email', $request['username'])
                ->first();

            if (!$user) {
                throw new \Exception('Invalid Login Credentials !', 401);
            }

            if (!$user->validateForPassportPasswordGrant($request->password)) {
                throw new \Exception('Invalid Login Credentials !', 401);
            }

            if (!$user->isActive()) {
                throw new \Exception('Your user account has been Deactivated, Please contact administration !', 401);
            }
            if ($user->isBanned()) {
                throw new \Exception('Your user account has been Banned, PLease contact administration !', 401);
            }
            if ($user->isSuspended()) {
                throw new \Exception('Your user account has been Suspended, Please contact administration !', 401);
            }

//            if(!$user->isPhoneVerified()){
////                throw ValidationException::withMessages([
////                    'is_phone_verified' => '0',
////                    'message' => 'Your phone number is still unverified !',
////                    'login_phone' => $user['login_phone'],
////                     'tokens' => $this->getTokenAndRefreshToken($request),
////                ]);
//                throw new \Exception('Your phone number is still unverified, Please contact administration !', 401);
//            }

            $tokens = $this->getTokenAndRefreshToken($request);

            $this->userService->updateLastLoginDetail($user);

            return sendSuccessResponse(
                'Authenticated',
                [
                    'user' => new MinimalUserResource($user),
                    //'account_status' => new B2CUserRegistrationStatusResource($user->userB2CRegistrationStatus),
                    'tokens' => $tokens
                ]
            );
        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                return sendErrorResponse($e->getMessage(), 422, $e->errors());
            }

            if ($e instanceof GuzzleException) {
                return sendErrorResponse($e->getMessage(), $e->getCode());
            }
            return sendErrorResponse($e->getMessage(), $e->getCode());
        }
    }


}

