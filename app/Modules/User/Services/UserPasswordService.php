<?php

namespace App\Modules\User\Services;

use App\Modules\User\Repositories\UserPasswordRepository;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserPasswordService
{
    private $userPasswordRepository;
    public function __construct(UserPasswordRepository $userPasswordRepository)
    {
        $this->userPasswordRepository = $userPasswordRepository;
    }
    
    public function changeFirstPassword($user, $validatedPassword){
        //check if user is first Logging

        if(!($user->is_first_login)){
            throw new Exception('Only first logging user can change first password', 400);
        }
        $validatedPassword['password'] = Hash::make($validatedPassword['password']);
        return $this->userPasswordRepository->changeFirstPassword($user, $validatedPassword);
    }

    public function changePassword($user, $validatedPassword){
        
        if(!Hash::check($validatedPassword['old_password'], $user->password)){
            throw new Exception('Provided Old Password is Incorrect', 400);
        }

        $validatedPassword['password'] = Hash::make($validatedPassword['password']);
        return $this->userPasswordRepository->changePassword($user, $validatedPassword);
    }
}