<?php

namespace App\Exceptions;

use App\Exceptions\Custom\IpAccessEnabledException;
use App\Exceptions\Custom\MaintenanceModeOnException;
use App\Exceptions\Custom\PermissionDeniedException;
use App\Exceptions\Custom\StoreAccessBarrierException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        if($exception instanceof ValidationException){
            if ($request->expectsJson()) {
                return sendErrorResponse($exception->getMessage(), 422, $exception->errors());
            }

        }

        if($exception instanceof TokenMismatchException){
            if ($request->expectsJson()) {
                return sendErrorResponse('Please Login Again ! Session Has Expired',419);
            }
            return redirect()->route('admin.login')->with('danger','Please Login Again ! Session Has Expired');
        }


        if($exception instanceof ThrottleRequestsException){
            if ($request->expectsJson()) {
                return sendErrorResponse('Too Many Attempts ! Slow your roll ',429);
            }
           // return response()->view('errors.405', [], 405);
        }


        if ($exception instanceof MethodNotAllowedHttpException) {
            if ($request->expectsJson()) {
                return sendErrorResponse('Sorry ! No Such Method Allowed !',405);
            }

            return response()->view('errors.405', [], 405);
        }


        if ($exception instanceof ModelNotFoundException) {
            if ($request->expectsJson()) {
                return sendErrorResponse('Sorry ! No Such Resource Found !',404);
            }

            return response()->view('errors.404', [], 404);
        }



        if ($exception instanceof NotFoundHttpException) {

            if ($request->expectsJson()) {
                return sendErrorResponse('Sorry ! Bad Url !',404);
            }

            return response()->view('errors.404', ['Page Not Found'], 404);
        }

        if($exception instanceof AuthenticationException){
            if ($request->expectsJson()) {

                return sendErrorResponse('Unauthenticated',401);
            }
        }




        if ($exception instanceof MaintenanceModeOnException) {

            if ($request->expectsJson()) {
                return sendErrorResponse('We are in maintenance mode .',503);
            }

           abort(503, 'We are in maintenance mode .');
        }

        if ($exception instanceof PermissionDeniedException) {
            $exceptionMessage = $exception->getMessage() ?: 'Permission Denied';
            if ($request->expectsJson()) {
                return sendErrorResponse($exceptionMessage,403,$exception->getData());
            }

           abort(403, $exceptionMessage);
        }

        if ($exception instanceof AccessDeniedHttpException) {

            if ($request->expectsJson()) {
                return sendErrorResponse('Access Denied',403);
            }

           abort(403, 'Access Denied');
        }

        if ($exception instanceof StoreAccessBarrierException) {

            if ($request->expectsJson()) {
                return sendErrorResponse($exception->getMessage(),403);
            }

           abort(403, $exception->getMessage());
        }

        if ($exception instanceof IpAccessEnabledException) {

            if ($request->expectsJson()) {
                return sendErrorResponse('Access Denied : not allowed for you ip address ',403);
            }

            abort(403, 'Access Denied : not allowed for you ip address ');
        }




        return parent::render($request, $exception);
    }


}
