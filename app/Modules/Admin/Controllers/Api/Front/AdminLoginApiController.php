<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/24/2020
 * Time: 4:24 PM
 */

namespace App\Modules\Admin\Controllers\Api\Front;


use App\Modules\Application\Controllers\BaseAuthenticationController;
use App\Modules\User\Models\User;
use App\Modules\User\Resources\MinimalUserResource;
use App\Modules\User\Services\UserService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminLoginApiController extends BaseAuthenticationController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function loginAdmin(Request $request)
    {

        try {
            $request->validate([
                'username' => 'required|email',
                'password' => 'required',
            ]);


            $user = User::select('user_code','name','login_email','password','user_type_code')
                ->whereHas('userType', function ($query) {
                $query->whereIn('slug', ['super-admin']);
            })
                ->where('login_email',$request->username)
                ->first();
            if (!$user) {
                throw new \Exception('Invalid Login Credentials !', 401);
            }

            if (!$user->validateForPassportPasswordGrant($request->password)) {
                throw new \Exception('Invalid Login Credentials !', 401);
            }

            //$tokens = $user->createToken('MyToken'.$user->user_code)->accessToken;
            $tokens = $this->getTokenAndRefreshToken($request);

            $this->userService->updateLastLoginDetail($user);

            return sendSuccessResponse(
                'Authenticated',
                [
                    'user' => [
                        'name'=>$user->name,
                        'user_type'=>$user->userType->slug,
                        'login_email'=>$user->login_email,
                    ],
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
