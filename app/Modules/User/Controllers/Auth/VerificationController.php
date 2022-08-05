<?php

namespace App\Modules\User\Controllers\Auth;
use App\Modules\User\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class VerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    use verifiesEmails;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth')->only('resend');
//        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }


    public function verify(Request $request)
    {
        auth()->loginUsingId($request->route('id'));

        if (! hash_equals((string) $request->route('id'), (string) $request->user()->getKey())) {
            throw new AuthorizationException;
        }

        if (! hash_equals((string) $request->route('hash'), sha1($request->user()->getEmailForVerification()))) {
            throw new AuthorizationException;
        }


        if ($request->user()->hasVerifiedEmail()) {
//            return $request->wantsJson()
//                ? new JsonResponse([], 204)
//                : redirect($this->redirectPath());
//            return sendErrorResponse('Failed to verify your email. Please login and try again!',['data'=>$request->user()->login_email]);
            return sendSuccessResponse('Your email is already verified. Thank you for your support.');
        }
//
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }else{
            return sendErrorResponse('Failed to verify your email. Please login and try again!');
        }

        if ($response = $this->verified($request)) {

            return sendSuccessResponse('We have successfully verified your email. Thank you for your support.');
        }


        return sendSuccessResponse('We have successfully verified your email. Thank you for your support.');

    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


//user must be logged in
    public function resend(Request $request)
    {
        $user = User::where('user_code', '=', $request->route('id'))->first();
        if ($user === null) {
            return response()->json(['message' => 'User Not Found'], 404);
        }else{
            auth()->loginUsingId($request->route('id'));

            if ($request->user()->hasVerifiedEmail()) {
                return response(['message'=>'Already Verified']);
            }

            try{
                $request->user()->sendEmailVerificationNotification();
            }catch(\Exception $exception){
                return sendErrorResponse('Failed to send the email verification link. Please try again');
            }

            if($request->wantsJson()){
                return sendSuccessResponse('Email verification link sent successfully. Please view your email.');

            }

            return sendSuccessResponse('Email verification link sent successfully. Please view your email.');

        }

    }

}
