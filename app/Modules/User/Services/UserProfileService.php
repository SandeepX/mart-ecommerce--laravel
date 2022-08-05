<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/8/2020
 * Time: 12:40 PM
 */

namespace App\Modules\User\Services;


use App\Modules\User\Models\User;
use App\Modules\User\Repositories\UserProfileRepository;

use Exception;
use DB;

class UserProfileService
{

    private $userProfileRepository;

    public function __construct(UserProfileRepository $userProfileRepository)
    {
        $this->userProfileRepository = $userProfileRepository;
    }

    public function updateAvatar(User $user,$validatedAvatar){

        try{
            DB::beginTransaction();
            $this->userProfileRepository->updateAvatar($user,$validatedAvatar);
            DB::commit();
            return $user;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}