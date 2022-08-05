<?php

namespace App\Modules\User\Repositories;

use Illuminate\Support\Facades\Auth;

class UserPasswordRepository 
{
    public function changeFirstPassword($user, $validatedPassword){
        $user->update($validatedPassword);
        $user->is_first_login = 0;
        $user->save();

        // //revoke the token of user
        // $user->tokens->each(function($token, $key){
        //     $token->delete();
        // });

        return $user;
    }

    public function changePassword($user, $validatedPassword){
        
        $user->update($validatedPassword);

        // //revoke the token of user
        // $user->tokens->each(function($token, $key){
        //     $token->delete();
        // });

        return $user;
        
    }
}