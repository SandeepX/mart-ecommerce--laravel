<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/8/2020
 * Time: 12:43 PM
 */

namespace App\Modules\User\Repositories;


use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\User\Models\User;

use Exception;

class UserProfileRepository
{
    use ImageService;

    public function updateAvatar(User $user,$validatedAvatar){

        $fileNameToStore='';
        try{

            $fileNameToStore = $this->storeImageInServer($validatedAvatar, User::AVATAR_UPLOAD_PATH);

            $user->update(['avatar'=>$fileNameToStore]);

        }catch (Exception $e){

            $this->deleteImageFromServer(User::AVATAR_UPLOAD_PATH,$fileNameToStore);
            throw $e;
        }
    }
}