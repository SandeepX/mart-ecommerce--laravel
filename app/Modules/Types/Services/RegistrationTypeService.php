<?php

namespace App\Modules\Types\Services;

use App\Modules\Types\Repositories\RegistrationTypeRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class RegistrationTypeService
{
    protected $registrationTypeRepository;

    public function __construct(RegistrationTypeRepository $registrationTypeRepository)
    {
        $this->registrationTypeRepository = $registrationTypeRepository;
    }
    public function getAllRegistrationTypes(){
        return $this->registrationTypeRepository->getAllRegistrationTypes();
    }

    public function getAllActiveRegistrationTypes(){
        return $this->registrationTypeRepository->getAllRegistrationTypes(true);
    }



    public function findRegistrationTypeById($regTypeId, $with = [])
    {
        return $this->registrationTypeRepository->findRegistrationTypeById($regTypeId, $with);
    }

    public function findRegistrationTypeByCode($regTypeCode, $with = [])
    {
        return $this->registrationTypeRepository->findRegistrationTypeByCode($regTypeCode, $with);
    }

    public function findOrFailRegistrationTypeById($regTypeId, $with = [])
    {
        return $this->registrationTypeRepository->findOrFailRegistrationTypeById($regTypeId, $with);
    }

    public function findOrFailRegistrationTypeByCode($regTypeCode, $with = [])
    {
        return $this->registrationTypeRepository->findOrFailRegistrationTypeByCode($regTypeCode, $with);
    }

    public function storeRegistrationType($validated)
    {
        DB::beginTransaction();
        try {
            $regType = $this->registrationTypeRepository->storeRegistrationType($validated);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $regType;
    }

    public function updateRegistrationType($validated, $regTypeCode)
    {
        DB::beginTransaction();
        try {
            $regType = $this->registrationTypeRepository->findOrFailRegistrationTypeByCode($regTypeCode);
            $this->registrationTypeRepository->updateRegistrationType($validated, $regType);
            DB::commit();
            return $regType;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }

    public function deleteRegistrationType($regTypeCode)
    {
        DB::beginTransaction();
        try {
            $regType = $this->registrationTypeRepository->findOrFailRegistrationTypeByCode($regTypeCode);
            $regType = $this->registrationTypeRepository->delete($regType);
            DB::commit();
            return $regType;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }






}