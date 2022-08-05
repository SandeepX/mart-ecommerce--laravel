<?php

namespace App\Modules\AdminWarehouse\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Modules\User\Models\User;

class WarehousePasswordResetController extends Controller
{

    use ResetsPasswords;


    private $module;

    public  function __construct()
    {
        $this->module = 'AdminWarehouse::';
        $this->middleware('guest')->except('logout');
    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    private $redirectTo = '/warehouse/login';



    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        $request->validate([ 'login_email' => 'required|email']);

        return view($this->module . 'auth.reset-password.reset-password')
            ->with(['token' => $token, 'login_email' => $request->login_email]);
    }


    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        $user = User::where('login_email',$request->login_email)->first();

        //dd($user);

        if(!$user){
            return redirect()->back()->with('danger','We cannot find a user with that email address');
        }

        if(!$user->isWarehouseAdminOrUser()){
            return redirect()->back()->with('danger','Sorry ! You are not allowed . ');
        }

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    private function rules()
    {
        return [
            'token' => 'required',
            'login_email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ];
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
        return $request->only(
            'login_email',
            'password',
            'password_confirmation',
            'token'
        );
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

        $user->setRememberToken(Str::random(60));

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
    private function sendResetResponse(Request $request, $response)
    {
        if ($request->wantsJson()) {
            return new JsonResponse(['message' => trans($response)], 200);
        }

        return redirect($this->redirectPath())
            ->with('success', trans($response));
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    private function sendResetFailedResponse(Request $request, $response)
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


}
