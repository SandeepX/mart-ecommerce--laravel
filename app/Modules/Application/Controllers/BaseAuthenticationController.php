<?php

/**
 * Created by PhpStorm.
 * User: shramik
 * Date: 8/20/20
 * Time: 10:58 AM
 */

namespace App\Modules\Application\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Exception;


class BaseAuthenticationController
{


    public function getTokenAndRefreshToken(Request $request)
    {
        try {

            $appEnv = config('app.env');

            if ($appEnv == 'local') {

                $request->request->add([
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request['username'],
                    'password' => $request['password'],
                ]);

                //Post to "oauth/token"

                $tokenRequest = $request->create(config('services.passport.login_endpoint'), "POST");
                $instance = Route::dispatch($tokenRequest);
                $response = json_decode($instance->getContent());

            } else {

                $http = new \GuzzleHttp\Client();
                $oAuthUrl = config('services.passport.login_endpoint');
                $response = $http->post($oAuthUrl, [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => config('services.passport.client_id'),
                        'client_secret' => config('services.passport.client_secret'),
                        'username' => $request['username'],
                        'password' => $request['password']
                    ],
                ]);

                $response = json_decode($response->getBody());
            }
            if (!property_exists($response, "access_token")) {
               throw new Exception('Invalid Login Credentials',401);
            }



        } catch (Exception $exception) {
            throw $exception;
        }

        return $response;
    }

    public function generateRefreshToken(Request $request, $oldRefreshToken)
    {

        try {
            $appEnv = config('app.env');

            if ($appEnv == 'local') {
                $request->request->add([
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $oldRefreshToken,
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret')
                ]);

                //Post to "oauth/token"

                $tokenRequest = $request->create(config('services.passport.login_endpoint'), "POST");

                $instance = Route::dispatch($tokenRequest);
                $response = json_decode($instance->getContent(),true);
            } else {
                $http = new \GuzzleHttp\Client();
                $oAuthUrl = config('services.passport.login_endpoint');
                $response = $http->post($oAuthUrl, [
                    'form_params' => [
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $oldRefreshToken,
                        'client_id' => config('services.passport.client_id'),
                        'client_secret' => config('services.passport.client_secret')
                    ],
                ]);
                $response = json_decode($response->getBody(),true);
            }

        } catch (Exception $exception) {
            throw $exception;
        }

        return $response;
    }
}
