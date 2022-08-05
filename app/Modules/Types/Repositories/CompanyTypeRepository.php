<?php

namespace App\Modules\Types\Repositories;

use App\Modules\Types\Models\CompanyType;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CompanyTypeRepository
{
    public function getAllCompanyTypes($activeStatus=false){

        $companyTypes = CompanyType::query();

        if ($activeStatus){
            $companyTypes = $companyTypes->active();
        }
        return $companyTypes->latest()->get();
    }


    public function getAllCompanyTypesWithTrashed($with = [])
    {
        return CompanyType::with($with)->withTrashed()->latest()->get();
    }

    public function findCompanyTypeById($companyTypeId, $with = [])
    {
        return CompanyType::with($with)->where('id', $companyTypeId)->first();
    }

    public function findCompanyTypeBySlug($companyTypeSlug, $with = [])
    {
        return CompanyType::with($with)->where('slug', $companyTypeSlug)->first();
    }


    public function findCompanyTypeByCode($companyTypeCode, $with = [])
    {
        return CompanyType::with($with)->where('company_type_code', $companyTypeCode)->first();
    }


    public function findOrFailCompanyTypeById($companyTypeId, $with = [])
    {
        if(!$companyType = $this->findCompanyTypeById($companyTypeId,$with)){
            throw new ModelNotFoundException('No Such CompanyType Found');
        }
        return $companyType;
    }

    public function findOrFailCompanyTypeBySlug($companyTypeSlug, $with = [])
    {
        if(!$companyType = $this->findCompanyTypeBySlug($companyTypeSlug,$with)){
            throw new ModelNotFoundException('No Such CompanyType Found');
        }
        return $companyType;
    }


    public function findOrFailCompanyTypeByCode($companyTypeCode, $with = [])
    {
        if(!$companyType = $this->findCompanyTypeByCode($companyTypeCode,$with)){
            throw new ModelNotFoundException('No Such CompanyType Found');
        }
        return $companyType;
    }


    public function storeCompanyType($validated)
    {
        $authUserCode = getAuthUserCode();
        $companyType = new CompanyType;
        $validated['company_type_code'] = $companyType->generateCompanyTypeCode();
        $validated['slug'] = make_slug($validated['company_type_name']);
        $validated['created_by'] = $authUserCode;
        $validated['updated_by'] = $authUserCode;
        $companyType = CompanyType::create($validated);
        return $companyType->fresh();

    }

    public function updateCompanyType($validated, $companyType)
    {
        $validated['slug'] = make_slug($validated['company_type_name']);
        $validated['updated_by'] = getAuthUserCode();
        $companyType->update($validated);
        return $companyType;

    }

    public function delete($companyType)
    {
        $companyType->delete();
        $companyType->deleted_by= getAuthUserCode();
        $companyType->save();
        return $companyType;
    }





}