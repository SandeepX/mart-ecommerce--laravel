<?php

namespace App\Modules\Types\Repositories;

use App\Modules\Types\Models\RegistrationType;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RegistrationTypeRepository
{
    public function getAllRegistrationTypes($activeStatus=false){
        $registrationTypes = RegistrationType::query();
        if ($activeStatus){
            $registrationTypes = $registrationTypes->active();
        }

        return $registrationTypes->latest()->get();
    }


    public function getAllTrashedRegistrationTypes($with = [])
    {
        return RegistrationType::with($with)->withTrashed()->latest()->get();
    }

    public function findRegistrationTypeById($registrationTypeId, $with = [])
    {
        return RegistrationType::with($with)->where('id', $registrationTypeId)->first();
    }

    public function findRegistrationTypeBySlug($registrationTypeSlug, $with = [])
    {
        return RegistrationType::with($with)->where('slug', $registrationTypeSlug)->first();
    }


    public function findRegistrationTypeByCode($registrationTypeCode, $with = [])
    {
        return RegistrationType::with($with)->where('registration_type_code', $registrationTypeCode)->first();
    }


    public function findOrFailRegistrationTypeById($registrationTypeId, $with = [])
    {
        if(!$registrationType = $this->findRegistrationTypeById($registrationTypeId,$with)){
            throw new ModelNotFoundException('No Such Company Registration Type Found');
        }
        return $registrationType;
    }

    public function findOrFailRegistrationTypeBySlug($registrationTypeSlug, $with = [])
    {
        if(!$registrationType = $this->findRegistrationTypeBySlug($registrationTypeSlug,$with)){
            throw new ModelNotFoundException('No Such Company Registration Type Found');
        }
        return $registrationType;
    }


    public function findOrFailRegistrationTypeByCode($registrationTypeCode, $with = [])
    {
        if(!$registrationType = $this->findRegistrationTypeByCode($registrationTypeCode,$with)){
            throw new ModelNotFoundException('No Such Company Registration Type Found');
        }
        return $registrationType;
    }


    public function storeRegistrationType($validated)
    {
        $authUserCode = getAuthUserCode();
        $registrationType = new RegistrationType;
        $validated['registration_type_code'] = $registrationType->generateRegistrationTypeCode();
        $validated['slug'] = make_slug($validated['registration_type_name']);
        $validated['created_by'] = $authUserCode;
        $validated['updated_by'] = $authUserCode;
        $registrationType = RegistrationType::create($validated);
        return $registrationType->fresh();

    }

    public function updateRegistrationType($validated, $registrationType)
    {
        $validated['slug'] = make_slug($validated['registration_type_name']);
        $validated['updated_by'] = getAuthUserCode();
        $registrationType->update($validated);
        return $registrationType;

    }

    public function delete($registrationType)
    {
        $registrationType->delete();
        $registrationType->deleted_by= getAuthUserCode();
        $registrationType->save();
        return $registrationType;
    }





}