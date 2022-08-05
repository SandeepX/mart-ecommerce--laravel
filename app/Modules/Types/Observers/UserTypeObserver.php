<?php

namespace App\Modules\Types\Observers;

use App\Modules\Types\Models\UserType;

class UserTypeObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(UserType $userType)
    {

    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(UserType $userType)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(UserType $userType)
    {
        if(!$userType->is_active){
            abort(401,'You are not Allowed to Delete This User Type');
        }
    }

    /**
     * Handle the User "forceDeleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function forceDeleted(UserType $userType)
    {
        if(!$userType->is_active){
            abort(401,'You are not Allowed to Delete This User Type');
        }
    }
}