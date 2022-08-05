<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/24/2020
 * Time: 4:24 PM
 */

namespace App\Modules\Store\Controllers\Api\Front\Auth;


use App\Modules\Application\Controllers\BaseAuthenticationController;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use App\Modules\Store\Models\Store;
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

class StoreAuthenticationApiController extends BaseAuthenticationController
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

    public function loginStore(Request $request)
    {

        try {
            $request->validate([
                'username' => 'required|email',
                'password' => 'required',
                'fcm_token' => 'nullable|string'
            ]);

            $connectedWarehouses = [];

            $user = User::whereHas('userType', function ($query) {
                $query->where('slug', 'store');
            })->whereHas('store')
                ->with(['userType','store'])
                ->where('login_email', $request['username'])
                ->first();

            if (!$user) {
                throw new \Exception('Invalid Login Credentials !', 401);
            }
            if($user->store->is_active == 0)
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

            if(!$user->isAccountVerified()){
                return sendErrorResponse('Email or Phone Should be verified !',400,
                    [
                        'reason' => 'email-or-phone-not-verified',
                        'login_email' => $user->login_email,
                        'login_phone' =>$user->login_phone,
                    ]);
            }


            //$tokens = $user->createToken('MyToken'.$user->user_code)->accessToken;
            $tokens = $this->getTokenAndRefreshToken($request);

            $this->userService->updateLastLoginDetail($user);

            $fcmToken = isset($request['fcm_token']) ? $request['fcm_token'] : NULL;

            $existedTokens = ($this->userFcmTokenService->getFcmTokenOfUserByUserCode($user->user_code))->pluck('fcm_token')->toArray();
            if($fcmToken && !in_array($fcmToken,$existedTokens)){
                $this->userFcmTokenService->saveUserFcmTokenDetails($user,$fcmToken);
            }

            $connectedWarehouse = StoreWarehouseHelper::getFirstConnectedWarehouse($user->store->store_code);
            if($connectedWarehouse){
               array_push($connectedWarehouses,[
                   'warehouse_name'=>$connectedWarehouse->warehouse_name,
                   'warehouse_code'=>$connectedWarehouse->warehouse_code
               ]);
            }
//


            $fcmToken = isset($request['fcm_token']) ? $request['fcm_token'] : NULL;
            if($fcmToken && $user->fcm_token != $fcmToken){
                $this->userService->updateFCMTokenOfUser($user,$fcmToken);
            }

            return sendSuccessResponse(
                'Authenticated',
                [
                    'user' => new MinimalUserResource($user),
                    'store_details' => new MinimalStoreResource($user->store),
                    'account_status' => new StoreAccountStatusResource($user->store),
                    'connected_warehouses'=>$connectedWarehouses,
                    'tokens' => $tokens
                ]
            );
        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                // return response()->json($e->errors());
                return sendErrorResponse($e->getMessage(), 422, $e->errors());
            }

            if ($e instanceof GuzzleException) {
                // return response()->json($e->errors());
                return sendErrorResponse($e->getMessage(), $e->getCode());
            }
            return sendErrorResponse($e->getMessage(), $e->getCode());
        }
    }

    // public function logout(){
    //     Auth::guard('api')->logout();
    // }

}
