<?php

namespace App\Modules\Types\Services;

use App\Modules\Types\Repositories\UserTypeRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class UserTypeService
{
    protected $userTypeRepository;

    public function __construct(UserTypeRepository $userTypeRepository)
    {
        $this->userTypeRepository = $userTypeRepository;
    }
    public function getAllUserTypes(){
        return $this->userTypeRepository->getAllUserTypes();
    }

    public function getAllActiveUserTypes(){
        return $this->userTypeRepository->getAllActiveUserTypes();
    }

    public function findUserTypeById($userTypeId, $with = [])
    {
        return $this->userTypeRepository->findUserTypeById($userTypeId, $with);
    }

    public function findUserTypeByCode($userTypeCode, $with = [])
    {
        return $this->userTypeRepository->findUserTypeByCode($userTypeCode, $with);
    }

    public function findOrFailUserTypeById($userTypeId, $with = [])
    {
        return $this->userTypeRepository->findUserTypeById($userTypeId, $with);
    }

    public function findOrFailUserTypeByCode($userTypeCode, $with = [])
    {
        return $this->userTypeRepository->findOrFailUserTypeByCode($userTypeCode, $with);
    }

    public function storeUserType($validated)
    {
        DB::beginTransaction();
        try {
            $userType = $this->userTypeRepository->storeUserType($validated);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $userType;
    }

    public function updateUserType($validated, $userTypeCode)
    {
        DB::beginTransaction();
        try {
            $userType = $this->userTypeRepository->findOrFailUserTypeByCode($userTypeCode);
            $this->userTypeRepository->updateUserType($validated, $userType);
            DB::commit();
            return $userType;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }

    public function deleteUserType($userTypeCode)
    {
        DB::beginTransaction();
        try {
            $userType = $this->userTypeRepository->findOrFailUserTypeByCode($userTypeCode);
            $userType = $this->userTypeRepository->delete($userType);
            DB::commit();
            return $userType;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }






}
