<?php

namespace App\Modules\User\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class UserForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;


    public  function __construct()
    {
        $this->middleware('guest')->except('logout');
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

        try {
            $response = $this->broker()->sendResetLink(
                $request->only('login_email')
            );

            if ($response === Password::RESET_LINK_SENT) {
                return sendSuccessResponse(trans($response));
            }

            throw new Exception(trans($response));
            
        } catch (Exception $ex) {
            return sendErrorResponse($ex->getMessage());
        }

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
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }
}
