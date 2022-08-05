<?php

namespace App\Modules\AdminWarehouse\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\User\Models\User;
use App\Modules\User\Services\UserService;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class WarehouseForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    protected $module;

    public  function __construct()
    {
        $this->module = 'AdminWarehouse::';
        $this->middleware('guest')->except('logout');
    }


    public function showForgotPasswordPage()
    {
        return view($this->module.'auth.forgot-password.forgot-password');
    }


    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $user = User::where('login_email',$request->login_email)->first();


        if(!$user){
            return redirect()->back()->with('danger','We cannot find a user with that email address');
        }

        if(!$user->isWarehouseAdminOrUser()){
            return redirect()->back()->with('danger','Sorry ! You are not allowed . ');
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        $response = $this->broker()->sendResetLink(
            $request->only('login_email')
        );


        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }


    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function validateEmail(Request $request)
    {
        $request->validate(['login_email' => 'required|email']);
    }



    /**
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    private function sendResetLinkResponse(Request $request, $response)
    {
        return $request->wantsJson()
            ? new JsonResponse(['message' => trans($response)], 200)
            : back()->with('success', trans($response));
    }



    private function sendResetLinkFailedResponse(Request $request, $response)
    {
        if ($request->wantsJson()) {
            throw ValidationException::withMessages([
                'login_email' => [trans($response)],
            ]);
        }

        return back()
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
