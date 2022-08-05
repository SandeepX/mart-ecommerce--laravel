<?php

/**
 * Created by PhpStorm.
 * User: Sandeep
 * Date: 22/10/2021
 * Time: 12:16 PM
 */


namespace App\Modules\SupportAdmin\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Modules\User\Models\User;
use App\Modules\User\Services\UserService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class SupportAdminLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $module = 'SupportAdmin::';
    protected $view;

    protected $redirectTo = 'support-admin/dashboard';
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('guest')->except('logout');
        $this->userService = $userService;
    }

    public function showSupportAdminLoginForm()
    {
        return view($this->module . 'auth.login.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $user = User::where('login_email', $request->get('login_email'))
            ->whereHas('userType', function ($query) {
                $query->where('slug', 'support-admin');
            })->first();

        if(!$user){
            return redirect()->back()->with('danger','Invalid Login Credentials !');
        }

        if(!$user->isActive()){
            return redirect()->back()->with('danger','Your user account has been Deactivated, Please contact administration !');
        }
        if($user->isBanned()){
            return redirect()->back()->with('danger','Your user account has been Banned, PLease contact administration !');
        }
        if($user->isSuspended()){
            return redirect()->back()->with('Your user account has been Suspended, Please contact administration !');
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $this->userService->updateLastLoginDetail($user);
            return $this->sendLoginResponse($request);

        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect(route('support-admin.login'));
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'login_email';
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectTo);
    }
}


