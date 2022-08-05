<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/8/2020
 * Time: 12:20 PM
 */

namespace App\Modules\User\Controllers\Api\Frontend;

use App\Modules\User\Requests\AvatarUpdateRequest;
use App\Modules\User\Resources\MinimalUserResource;
use App\Modules\User\Resources\UserProfileResource;
use App\Modules\User\Services\UserProfileService;
use Exception;

class ProfileController
{
    private $userProfileService;

    public function __construct(UserProfileService $userProfileService)
    {
        $this->userProfileService= $userProfileService;
    }

    public function updateAvatar(AvatarUpdateRequest $request){
        try{
            $validatedData = $request->validated();
            $this->userProfileService->updateAvatar(auth()->user(),$validatedData['avatar']);
            return sendSuccessResponse('Avatar updated successfully');
        }catch(Exception $exception){
            return sendErrorResponse([$exception->getMessage()], $exception->getCode());
        }
    }

    public function getUserAccountInformation(){
        return new UserProfileResource(auth()->user());
    }





}