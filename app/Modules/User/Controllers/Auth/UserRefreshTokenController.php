<?php

namespace App\Modules\User\Controllers\Auth;

use App\Modules\Application\Controllers\BaseAuthenticationController;
use Illuminate\Http\Request;
use Exception;

class UserRefreshTokenController extends BaseAuthenticationController
{
   

    public function getAccessTokenFromRefreshToken(Request $request){
        $refresh_token = $request->refresh_token;
       
        $request->validate([
            'refresh_token' => 'required'
        ]);
        
        try{
              $response = $this->generateRefreshToken($request,$refresh_token);
              if(is_array($response) && isset($response['error']) && !isset($response['access_token'])){
                  throw new Exception('Bad Request Data',400);
              }
        }catch(Exception $ex){
            return sendErrorResponse($ex->getMessage(),$ex->getCode());
        }

        return $response;
      
    }
}