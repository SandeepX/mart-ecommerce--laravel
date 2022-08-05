<?php

namespace App\Modules\SalesManager\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\SalesManager\Requests\ManagerOTPVerificationRequest;
use App\Modules\SalesManager\Requests\ManagerProfileApiRequest\SalesManagerUpdateDocsRequest;
use App\Modules\SalesManager\Requests\ManagerProfileApiRequest\SalesManagerUpdateEmailRequest;
use App\Modules\SalesManager\Requests\ManagerProfileApiRequest\SalesManagerUpdatePhoneRequest;
use App\Modules\SalesManager\Requests\ManagerProfileApiRequest\SalesManagerUpdateProfileRequest;
use App\Modules\SalesManager\Resources\ManagerDetailResource;
use App\Modules\SalesManager\Services\ManagerOtpService;
use App\Modules\SalesManager\Services\SalesManagerService;
use App\Modules\SalesManager\Services\UserSalesManagerService;
use Exception;
use Illuminate\Http\Request;

class ManagerProfileController extends Controller
{
    private $salesManagerService;
    public  $userSalesManagerService;
    private $managerOTPService;

    public function __construct(
        SalesManagerService $salesManagerService,
        UserSalesManagerService $userSalesManagerService,
        ManagerOtpService $managerOTPService
    ){
        $this->salesManagerService = $salesManagerService;
        $this->userSalesManagerService = $userSalesManagerService;
        $this->managerOTPService = $managerOTPService;
    }

    public function updateManagerProfile(SalesManagerUpdateProfileRequest $request,SalesManagerUpdateDocsRequest $userDocRequest)
    {
        try{
            $userCode = getAuthUserCode();
            $managerCode = getAuthManagerCode();
            $userDetail = $this->salesManagerService->getManagerDetail($userCode);
            $managerDetail = $this->salesManagerService->findOrFailSalesManagerByCodeWith($managerCode);
            $validatedUserData = $request->validated();
            $validatedUserDocData = $userDocRequest->validated();
            $this->userSalesManagerService->updateSalesManagerProfile($validatedUserData, $validatedUserDocData,$managerDetail,$userDetail);
            $data = new ManagerDetailResource($managerDetail);
            return sendSuccessResponse('Sales Manger profile updated',$data);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function generatePhoneVerificationOTP(){
        try{
            $this->managerOTPService->generatePhoneVerificationOTP();
            return sendSuccessResponse('Your otp has successfully sent to you phone ');
        }catch (Exception $exception){
           return sendErrorResponse($exception->getMessage(),400);
        }
    }

    public function generateEmailVerificationOTP(){
        try{
            $this->managerOTPService->generateEmailVerificationOTP();
            return sendSuccessResponse('Your otp code has successfully sent to you email');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),400);
        }

    }

    public function verifyPhoneOTP(ManagerOTPVerificationRequest $request){
        try{
            $validatedData = $request->validated();
            $this->managerOTPService->verifyPhoneOTP($validatedData);
            return sendSuccessResponse('Your phone has verified successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),400);
        }
    }

    public function verifyEmailOTP(ManagerOTPVerificationRequest $request){
        try{
            $validatedData = $request->validated();
            $this->managerOTPService->verifyEmailOTP($validatedData);
            return sendSuccessResponse('Your email has verified successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),400);
        }
    }

    public function updateManagerEmail(SalesManagerUpdateEmailRequest $request){
        try{
            $validatedData = $request->validated();
            $userCode = getAuthUserCode();
            $manager =  $this->userSalesManagerService->updateUserSalesManagerEmail($userCode,$validatedData);
            $data  = [
                 'login_email' => $manager->manager_email,
                 'is_email_verified' => (isset($manager->email_verified_at)) ? 1 : 0
            ];
            return sendSuccessResponse('Your email has successfully updated',$data);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),400);
        }
    }

    public function updateManagerPhone(SalesManagerUpdatePhoneRequest $request){
        try{
            $validatedData = $request->validated();
            $userCode = getAuthUserCode();
            $manager =  $this->userSalesManagerService->updateUserSalesManagerPhone($userCode,$validatedData);
            $data  = [
                'login_phone' => $manager->manager_phone_no,
                'is_phone_verified' => (isset($manager->phone_verified_at)) ? 1 : 0
            ];
            return sendSuccessResponse('Your email has successfully updated',$data);
        }catch (Exception $exception){
          return sendErrorResponse($exception->getMessage(),400);
        }
    }

}
