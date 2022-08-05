<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/24/2020
 * Time: 4:24 PM
 */

namespace App\Modules\SalesManager\Controllers\Api\Front\Auth;


use App\Modules\Application\Controllers\BaseAuthenticationController;
use App\Modules\SalesManager\Resources\MinimalManagerResource;
use App\Modules\SalesManager\Resources\SalesManagerRegistrationStatusResource;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use App\Modules\Store\Resources\MinimalStoreResource;
use App\Modules\Store\Resources\StoreAccountStatusResource;
use App\Modules\User\Models\User;
use App\Modules\User\Resources\MinimalUserResource;
use App\Modules\User\Services\UserFcmTokenService;
use App\Modules\User\Services\UserService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SalesManagerAuthenticationApiController extends BaseAuthenticationController
{
    private $userService;
    private $userFcmTokenService;

    public function __construct(
        UserService $userService,
        UserFcmTokenService $userFcmTokenService
    ){
        $this->userService = $userService;
        $this->userFcmTokenService = $userFcmTokenService;
    }

    public function loginSalesManager(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|email',
                'password' => 'required',
                'fcm_token' => 'nullable|string'
            ]);

            $user = User::whereHas('userType', function ($query) {
                $query->where('slug', 'sales-manager');
            })
                ->with(['userType','manager'])
                ->where('login_email', $request['username'])
                ->first();

            if (!$user) {
                throw new \Exception('Invalid Login Credentials !', 401);
            }

            if (!$user->validateForPassportPasswordGrant($request->password)) {
                throw new \Exception('Invalid Login Credentials !', 401);
            }

            //check for user's phone or email-address verified or not
//            if(!$user->email_verified_at && !$user->phone_verified_at){
//                throw new \Exception('Phone or Email should Be verified!', 401);
//            }

            if(!$user->isActive()){
                throw new \Exception('Your user account has been Deactivated, Please contact administration !', 401);
            }
            if($user->isBanned()){
                throw new \Exception('Your user account has been Banned, PLease contact administration !',401);
            }
            if($user->isSuspended()){
                throw new \Exception('Your user account has been Suspended, Please contact administration !',401);
            }

            $tokens = $this->getTokenAndRefreshToken($request);

            $this->userService->updateLastLoginDetail($user);


            if(!$user->isAccountVerified()){
                return sendErrorResponse('Email or Phone Should be verified !',400,
                    [
                        'reason' => 'email-or-phone-not-verified',
                        'login_email' => $user->login_email,
                        'login_phone' =>$user->login_phone,
                    ]);
            }

            $fcmToken = isset($request['fcm_token']) ? $request['fcm_token'] : NULL;
            $existedTokens = ($this->userFcmTokenService->getFcmTokenOfUserByUserCode($user->user_code))->pluck('fcm_token')->toArray();
            if($fcmToken && !in_array($fcmToken,$existedTokens)){
                $this->userFcmTokenService->saveUserFcmTokenDetails($user,$fcmToken);
            }

            return sendSuccessResponse(
                'Authenticated',
                [
                    'user' => new MinimalUserResource($user),
                    'manager_details' => new MinimalManagerResource($user->manager),
                    'account_status' => new SalesManagerRegistrationStatusResource($user->manager),
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
