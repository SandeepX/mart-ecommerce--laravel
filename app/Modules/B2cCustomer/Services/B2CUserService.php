<?php


namespace App\Modules\B2cCustomer\Services;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\B2cCustomer\Repositories\B2CUserRepositories;
use App\Modules\Types\Repositories\UserTypeRepository;
use App\Modules\User\Models\User;
use App\Modules\User\Repositories\UserDocRepository;
use App\Modules\User\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

use Exception;

class B2CUserService
{

    use ImageService;

    private $userTypeRepository;
    private $userRepo;
    private $userDocRepo;
    private $userB2CRegistrationStatusRepo;

    public function __construct(UserRepository $userRepo,
                                UserTypeRepository $userTypeRepository,
                                UserDocRepository $userDocRepo,
                                B2CUserRepositories $userB2CRegistrationStatusRepo
    )
    {
        $this->userRepo = $userRepo;
        $this->userTypeRepository = $userTypeRepository;
        $this->userDocRepo = $userDocRepo;
        $this->userB2CRegistrationStatusRepo = $userB2CRegistrationStatusRepo;
    }

    public function findOrFailB2CUserByCodeWith($userCode,$with=[])
    {
        $b2cUser =  $this->userRepo->findOrFailUserByCode($userCode,$with);
        if(!$b2cUser){
            throw new Exception('User not found !');
        }
        return $b2cUser;
    }


    public function storeUserB2C($validatedUserData)
    {
        $avatarNameToStore = '';
        try {
            DB::beginTransaction();

            $userType = $this->userTypeRepository->findB2CUserType();
            $validatedUserData['user_type_code'] = $userType->user_type_code;
            $validatedUserData['is_phone_verified'] = 0;

            if (isset($validatedUserData['avatar'])) {
                $avatarNameToStore = $this->storeImageInServer($validatedUserData['avatar'], User::AVATAR_UPLOAD_PATH);
                $validatedUserData['avatar'] = $avatarNameToStore;
            }
            //create B2C User
            $user = $this->userRepo->createFromApi($validatedUserData);

          //  create B2C user registration status
            $userRegistrationStatusData = [];
            $userRegistrationStatusData['user_code'] = $user->user_code;
            $userRegistrationStatusData['status'] = 'approved';
            $userRegistrationStatus = $this->userB2CRegistrationStatusRepo->storeRegistrationStatus($userRegistrationStatusData);

//            $user->sendEmailVerificationNotification();
            DB::commit();
            return $user;

        } catch (Exception $exception) {
            $this->deleteImageFromServer(User::AVATAR_UPLOAD_PATH, $avatarNameToStore);
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateProfile($validatedUserData,$validatedUserDocData,$userDetail)
    {

        $updatedAvatarNameToStore = '';
        try{
            DB::beginTransaction();

            if(isset($validatedUserData['avatar']) && !is_null($userDetail['avatar'])){
                $this->deleteImageFromServer(User::AVATAR_UPLOAD_PATH, $userDetail['avatar']);
                $updatedAvatarNameToStore = $this->storeImageInServer($validatedUserData['avatar'], User::AVATAR_UPLOAD_PATH);
                $validatedUserData['avatar'] = $updatedAvatarNameToStore;
            }
            if(isset($validatedUserData['avatar']) && is_null($userDetail['avatar'])){
                $updatedAvatarNameToStore = $this->storeImageInServer($validatedUserData['avatar'], User::AVATAR_UPLOAD_PATH);
                $validatedUserData['avatar'] = $updatedAvatarNameToStore;
            }

            $updatedUserProfile = $this->userRepo->update($validatedUserData,$userDetail);

            if(isset($validatedUserDocData['doc_name']) && count($validatedUserDocData['doc_name']) > 0){
                $validatedUserDocData['doc_name'] = array_shift($validatedUserDocData['doc_name']);
                $validatedUserDocData['doc_number'] = array_shift($validatedUserDocData['doc_number']);
                $validatedUserDocData['doc_issued_district'] = isset($validatedUserDocData['doc_issued_district']) ? $validatedUserDocData['doc_issued_district'] : null;

                //only citizen is sent from frontend with 0 index always front and 1 index always back
                if ($validatedUserDocData['doc_name'] == 'citizenship') {
                    $validatedUserDocData['doc_name'] = [];
                    array_push($validatedUserDocData['doc_name'], 'citizenship-front');
                    array_push($validatedUserDocData['doc_name'], 'citizenship-back');
                } else {
                    $validatedUserDocData['doc_name'] = explode(' ', $validatedUserDocData['doc_name']);
                }

                foreach($validatedUserDocData['doc_name'] as $key => $docName){
                    $getUserDocName = $this->userDocRepo->getUserDocByDocName($docName);
                    if($getUserDocName) {
                        $userDoc = $this->userDocRepo->updateDocument([
                            'user_code' => $updatedUserProfile->user_code,
                            'doc_name' => $getUserDocName['doc_name'],
                            'doc_number' => $validatedUserDocData['doc_number'],
                            'doc_file' => $validatedUserDocData['doc'][$key],
                            'doc_issued_district' => $validatedUserDocData['doc_issued_district'],
                        ],$getUserDocName);
                    }else{
                        $userDoc = $this->userDocRepo->storeDocument([
                            'user_code' => $updatedUserProfile->user_code,
                            'doc_name' => $validatedUserDocData['doc_name'][$key],
                            'doc_number' => $validatedUserDocData['doc_number'],
                            'doc_file' => $validatedUserDocData['doc'][$key],
                            'doc_issued_district' => $validatedUserDocData['doc_issued_district'],
                        ]);
                    }
                }
            }
             DB::commit();
             return $updatedUserProfile;
        } catch (Exception $exception) {
            $this->deleteImageFromServer(User::AVATAR_UPLOAD_PATH, $updatedAvatarNameToStore);
            DB::rollBack();
            throw $exception;
        }
    }

}


