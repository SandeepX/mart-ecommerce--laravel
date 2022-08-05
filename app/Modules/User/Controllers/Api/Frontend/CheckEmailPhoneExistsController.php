<?php

namespace App\Modules\User\Controllers\Api\Frontend;

use App\Modules\Application\Classes\EmailValidator;
use App\Modules\Application\Classes\PhoneNumberValidator;
use App\Modules\User\Requests\CheckUserEmailExistsRequest;
use App\Modules\User\Requests\CheckUserPhoneExistsRequest;
use App\Modules\User\Services\CheckUserEmailPhoneExistsService;

class CheckEmailPhoneExistsController
{
    protected $checkUserEmailPhoneExistsService;

    public function __construct(CheckUserEmailPhoneExistsService $checkUserEmailPhoneExistsService)
    {
        $this->checkUserEmailPhoneExistsService=$checkUserEmailPhoneExistsService;
    }

    public function checkEmailExists(CheckUserEmailExistsRequest $request){
       try{
           $validatedData = $request->validated();
         //  EmailValidator::validateEmail($validatedData['email']);
           $user =  $this->checkUserEmailPhoneExistsService->checkEmailExists($validatedData);
           $data = isset($user) ? true : false;
           $message = isset($user)?"The email address has been registered":"No account found with that email";
           return sendSuccessResponse($message,$data);
       }
       catch(\Exception $exception){
           return sendErrorResponse($exception->getMessage(), $exception->getCode());
       }
    }

    public function checkPhoneExists(CheckUserPhoneExistsRequest $request){

        try{

            $validatedData = $request->validated();
            //PhoneNumberValidator::validatePhoneNumber($validatedData['phone']);
            $user = $this->checkUserEmailPhoneExistsService->checkPhoneExists($validatedData);
            $data = isset($user) ? true : false;
            $message = isset($user) ? "The mobile number has been registered":"No account found with that phone number";
            return sendSuccessResponse($message,$data);
        }
        catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
