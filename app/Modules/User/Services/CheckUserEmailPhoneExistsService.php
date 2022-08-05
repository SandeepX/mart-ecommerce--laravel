<?php

namespace App\Modules\User\Services;


use App\Modules\User\Repositories\UserRepository;

class CheckUserEmailPhoneExistsService
{
    protected $userRepository;
    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

    public function checkEmailExists($validatedData){
        try{
            $user = $this->userRepository->findUserByEmail($validatedData);
            return $user;
        }catch(\Exception $exception){
            throw $exception;
        }
    }

    public function checkPhoneExists($validatedData){
        try{
            $user = $this->userRepository->findUserByPhone($validatedData);
            return $user;
        }catch(\Exception $exception){
            throw $exception;
        }
    }
}
