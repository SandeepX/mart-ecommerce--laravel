<?php

namespace App\Modules\Profile\Services;

use App\Modules\Profile\Repositories\UserProfileRepository;

class UserProfileService
{
    private $userProfileRepository;

    public function __construct(UserProfileRepository $userProfileRepository){
       $this->userProfileRepository = $userProfileRepository;
    }


}
