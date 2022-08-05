<?php


namespace App\Modules\B2cCustomer\Controllers\Api;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\B2cCustomer\Requests\UserDocsRequest;
use App\Modules\B2cCustomer\Requests\UserProfileUpdateRequest;
use App\Modules\B2cCustomer\Resources\B2CUserDetailResource;
use App\Modules\B2cCustomer\Services\B2CUserService;
use App\Modules\User\Services\UserService;
use Exception;

class B2CController extends BaseController
{
    private $b2cUserService;
    private $userService;

    public function __construct(B2CUserService $b2cUserService,UserService $userService)
    {
       $this->b2cUserService = $b2cUserService;
       $this->userService = $userService;
    }

    public function getProfile()
    {
        try{
            $userDetail = $this->userService->findUserByCode(getAuthUserCode());
            if(!$userDetail){
                throw new Exception('User Detail Not Found');
            }
            $data = new B2CUserDetailResource($userDetail);
            return sendSuccessResponse('Data found',$data);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function updateProfile(UserProfileUpdateRequest $request,UserDocsRequest $userDocRequest)
    {
        try{
            $userDetail = $this->b2cUserService->findOrFailB2CUserByCodeWith(getAuthUserCode());
            $validatedUserData = $request->validated();
            $validatedUserDocData = $userDocRequest->validated();
            $this->b2cUserService->updateProfile($validatedUserData, $validatedUserDocData,$userDetail);
            $data = new B2CUserDetailResource($userDetail);
            return sendSuccessResponse('Profile Updated Successfully',$data);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

}
