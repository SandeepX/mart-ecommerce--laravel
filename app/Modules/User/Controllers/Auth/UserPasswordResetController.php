<?php

namespace App\Modules\User\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\OTP\Repositories\OTPRepository;
use App\Modules\User\Requests\UserPassword\UserPasswordResetRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Modules\User\Models\User;
use Exception;


class UserPasswordResetController extends Controller
{
    private $otpRepository;
    public function __construct(OTPRepository $otpRepository)
    {
        $this->otpRepository = $otpRepository;
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */

//    public function reset(Request $request)
//    {
//        $request->validate($this->rules(), $this->validationErrorMessages());
//
//        $user = User::where('login_email', $request->login_email)
//            ->whereHas('vendor')
//            ->orWhereHas('store')
//            ->firstOrFail();
//
//        if (!$user) {
//          throw new Exception('Sorry cannot reset password for this user');
//        }
//
//        $userType = $user->isStoreUser() ? 'store' : 'vendor';
//
//
//        // Here we will attempt to reset the user's password. If it is successful we
//        // will update the password on an actual user model and persist it to the
//        // database. Otherwise we will parse the error and return the response.
//        $response = $this->broker()->reset(
//            $this->credentials($request),
//            function ($user, $password) {
//                $this->resetPassword($user, $password);
//            }
//        );
//
//
//
//        // If the password was successfully reset, we will redirect the user back to
//        // the application's home authenticated view. If there is an error we can
//        // redirect them back to where they came from with their error message.
//        return $response == Password::PASSWORD_RESET
//            ? $this->sendResetResponse($request, $response,$userType)
//            : $this->sendResetFailedResponse($request, $response);
//    }

    public function reset(UserPasswordResetRequest $request)
    {
        try{

        $validatedData = $request->validated();

      $loginField = isset($validatedData['login_phone']) ? 'phone' : 'email';

      $user = User::with('userType')->where('login_'.$loginField, $validatedData['login_'.$loginField])->first();
      if(!$user){
            throw new Exception('Invalid User',404);
      }

        $userType = $user->userType->slug;
        $userTypes = ['vendor','store','sales-manager'];
        if (!(in_array($userType,$userTypes))) {
            throw new Exception('Sorry cannot reset password for this user',403);
        }

       DB::beginTransaction();

        if(isset($validatedData['reset_method']) && $validatedData['reset_method'] == 'otp'){
            $entityDetails = $this->getEntityDetailsOfUser($user);
            $latestOTP = $this->otpRepository->getLatestUseAbleOTPForVerification(
                $entityDetails['entity'],
                $entityDetails['entity_code'],
                'forgot_password'
            );

            if(!$latestOTP || $latestOTP->purpose_verified != 1 || $latestOTP->purpose != 'forgot_password' ||  $latestOTP->useable == 0){
                throw new Exception('Invalid Request',403);
            }

            $this->resetPassword($user, $validatedData['password']);

            $this->otpRepository->makeOTPUnUsable($latestOTP);
            $response = Password::PASSWORD_RESET;

             DB::commit();
             return $this->sendResetResponse($response,$userType);


        }else{

            $response =   $this->broker()->reset(
                 $request->only('login_email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ]);
                    $user->save();
                    event(new PasswordReset($user));
                }
            );



            if($response == Password::PASSWORD_RESET){
                DB::commit();
                return  $this->sendResetResponse($response,$userType);
            }
           // dd(trans($response));

            throw new Exception(trans($response),400);


               // : $this->sendResetFailedResponse($request, $response);
        }


        }catch (\Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    private function rules()
    {
        $rules  = [];
        $rules['reset_method'] = ['nullable',Rule::in('token','otp')];
        $rules['token'] = [$this->get('reset_method') != 'otp' ? 'required' : 'nullable'];
        $rules['login_email'] = ['required_without:login_phone','email'];
        $rules['login_phone'] = ['required_without:login_email','integer','digits:10'];

        $rules['password'] = ['required','confirmed','min:8'];

        return $rules;
    }

    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    private function validationErrorMessages()
    {
        return [
            'token.required' => 'Token is required !',
            'login_email.required' => 'Email is required !',
            'login_email.email' => 'Please Provide Valid Email Address',
        ];
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function credentials(Request $request)
    {

       // `return`
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    private function resetPassword($user, $password)
    {
            $this->setUserPassword($user, $password);
            //$user->setRememberToken(Str::random(60));
            $user->save();
            event(new PasswordReset($user));

    }

    /**
     * Set the user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    private function setUserPassword($user, $password)
    {
        $user->password = Hash::make($password);
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    private function sendResetResponse( $response,$userType)
    {
        return sendSuccessResponse(trans($response),[
                'user_type' => $userType
            ]);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    private function sendResetFailedResponse(Request $request,$response)
    {
        if ($request->wantsJson()) {
            throw ValidationException::withMessages([
                'login_email' => [trans($response)],
            ]);
        }


        return redirect()->back()
            ->withInput($request->only('login_email'))
            ->withErrors(['login_email' => trans($response)]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }

    private function getEntityDetailsOfUser($user){
        $userTypeNameSpace = $user->userType->namespace;
        $data = [];
        $data['entity'] = strtolower(substr($userTypeNameSpace,(strrpos($userTypeNameSpace,'\\') + 1)));
        switch($data['entity']){
            case 'store':
                $data['entity_code'] = $user->store->store_code;
                break;
            case 'manager':
                $data['entity_code'] = $user->manager->manager_code;
                break;
            case 'vendor':
                $data['entity_code'] = $user->vendor->vendor_code;
                break;
            default:
                $data['entity_code'] = $user->user_code;
                break;
        }
        return $data;
    }
}
