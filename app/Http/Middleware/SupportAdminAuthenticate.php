<?php


namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class SupportAdminAuthenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            return sendErrorResponse('Unathenticated : Please Login First !', 401);
        }
        return route('support-admin.login');
    }
}

