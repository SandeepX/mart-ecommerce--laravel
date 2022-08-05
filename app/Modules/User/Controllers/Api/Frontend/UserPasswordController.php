<?php

namespace App\Modules\User\Controllers\Api\Frontend;

use App\Modules\User\Requests\UserPassword\UserFirstPasswordChangeRequest;
use App\Modules\User\Requests\UserPassword\UserPasswordChangeRequest;
use App\Modules\User\Resources\MinimalUserResource;
use App\Modules\User\Services\UserPasswordService;
use Exception;
use Illuminate\Support\Facades\DB;

class UserPasswordController
{
    private $userPasswordService;
    public function __construct(UserPasswordService $userPasswordService)
    {
        $this->userPasswordService = $userPasswordService;
    }

    public function changeFirstPassword(UserFirstPasswordChangeRequest $passwordRequest){
        $validatedPassword = $passwordRequest->validated();
        DB::beginTransaction();
        try{
            $user = $this->userPasswordService->changeFirstPassword(auth()->user(), $validatedPassword);
            $user = new MinimalUserResource($user);
            DB::commit();
            return sendSuccessResponse('Password Changed Successfully ! Now You can Login', $user);
        }catch(Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function changePassword(UserPasswordChangeRequest $passwordRequest){
        $validatedPassword = $passwordRequest->validated();
        DB::beginTransaction();
        try{
            $user = $this->userPasswordService->changePassword(auth()->user(), $validatedPassword);
            $user = new MinimalUserResource($user);
            DB::commit();
            return sendSuccessResponse('Password Changed Successfully', $user);
        }catch(Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
