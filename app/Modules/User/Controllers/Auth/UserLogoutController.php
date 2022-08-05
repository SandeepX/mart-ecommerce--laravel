<?php

namespace App\Modules\User\Controllers\Auth;

use App\Modules\Application\Controllers\BaseAuthenticationController;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class UserLogoutController extends BaseAuthenticationController
{
    /**
     * Logs out the user. We revoke access token and refresh token.
     * 
     */

    public function logoutAuthenticatedUser(Request $request)
    {
        try {
            $accessToken =  $request->user()->token();

            $refreshToken = DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $accessToken->id)
                ->update([
                    'revoked' => true
                ]);
                $accessToken->revoke();
            return sendSuccessResponse('Logout Successful');
        } catch (Exception $ex) {
            return sendErrorResponse('Could not logout the user : Something Went Wrong !');
        }
    }
}
