<?php


namespace App\Modules\B2cCustomer\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Modules\B2cCustomer\Requests\B2CUserRegisterRequest;
use App\Modules\B2cCustomer\Requests\UserDocsRequest;
use App\Modules\B2cCustomer\Resources\B2CUserRegistrationStatusResource;
use App\Modules\B2cCustomer\Services\B2CUserService;
use App\Modules\User\Resources\MinimalUserResource;
use Exception;

class B2CUserRegistrationController extends Controller
{

    private $userB2CService;

    public function __construct(B2CUserService $userB2CService)
    {
        $this->userB2CService = $userB2CService;
    }

    public function storeB2CUserFromApi(B2CUserRegisterRequest $userB2CRegisterRequest)
    {
        try {
            $validatedUserData = $userB2CRegisterRequest->validated();
            $user =$this->userB2CService->storeUserB2C($validatedUserData);

            $token['token_type'] = 'Bearer';
            $token['access_token'] = $user->createToken('api token')->accessToken;
            return sendSuccessResponse('New B2C User Registered Successfully',[
                    'user' => new MinimalUserResource($user),
                    'tokens' => $token
                ]
            );
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function findB2CUserAccountStatus()
    {
        try {
            $UserB2CRegistrationStatus = getAuthB2CRegistrationStatus();
            return sendSuccessResponse(
                'B2C User Account Status Fetched',
                new B2CUserRegistrationStatusResource($UserB2CRegistrationStatus)
            );
        } catch (\Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}

