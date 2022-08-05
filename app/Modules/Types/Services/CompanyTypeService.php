<?php

namespace App\Modules\Types\Services;

use App\Modules\Types\Repositories\CompanyTypeRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class CompanyTypeService
{
    protected $companyTypeRepository;

    public function __construct(CompanyTypeRepository $companyTypeRepository)
    {
        $this->companyTypeRepository = $companyTypeRepository;
    }
    public function getAllCompanyTypes(){
        return $this->companyTypeRepository->getAllCompanyTypes();
    }

    public function getAllActiveCompanyTypes(){
        return $this->companyTypeRepository->getAllCompanyTypes(true);
    }

    public function findCompanyTypeById($companyTypeId, $with = [])
    {
        return $this->companyTypeRepository->findCompanyTypeById($companyTypeId, $with);
    }

    public function findCompanyTypeByCode($companyTypeCode, $with = [])
    {
        return $this->companyTypeRepository->findCompanyTypeByCode($companyTypeCode, $with);
    }

    public function findOrFailCompanyTypeById($companyTypeId, $with = [])
    {
        return $this->companyTypeRepository->findCompanyTypeById($companyTypeId, $with);
    }

    public function findOrFailCompanyTypeByCode($companyTypeCode, $with = [])
    {
        return $this->companyTypeRepository->findOrFailCompanyTypeByCode($companyTypeCode, $with);
    }

    public function storeCompanyType($validated)
    {
        DB::beginTransaction();
        try {
            $companyType = $this->companyTypeRepository->storeCompanyType($validated);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $companyType;
    }

    public function updateCompanyType($validated, $companyTypeCode)
    {
        DB::beginTransaction();
        try {
            $companyType = $this->companyTypeRepository->findOrFailCompanyTypeByCode($companyTypeCode);
            $this->companyTypeRepository->updateCompanyType($validated, $companyType);
            DB::commit();
            return $companyType;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }

    public function deleteCompanyType($companyTypeCode)
    {
        DB::beginTransaction();
        try {
            $companyType = $this->companyTypeRepository->findOrFailCompanyTypeByCode($companyTypeCode);
            $CompanyType = $this->companyTypeRepository->delete($companyType);
            DB::commit();
            return $companyType;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }






}