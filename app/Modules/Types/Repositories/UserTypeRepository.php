<?php

namespace App\Modules\Types\Repositories;

use App\Modules\Types\Models\UserType;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserTypeRepository
{
    public function getAllUserTypes(){
        return UserType::latest()->get();
    }

    public function getAllActiveUserTypes(){
        return  UserType::where('is_active',1)->latest()->get();
    }

    public function getAllUserTypesWithTrashed($with = [])
    {
        return UserType::with($with)->withTrashed()->latest()->get();
    }

    public function findUserTypeById($userTypeId, $with = [])
    {
        return UserType::with($with)->where('id', ($userTypeId))->first();
    }

    public function findUserTypeBySlug($userTypeSlug, $with = [])
    {
        return UserType::with($with)->where('slug', $userTypeSlug)->first();
    }


    public function findUserTypeByCode($userTypeCode, $with = [])
    {
        return UserType::with($with)->where('user_type_code', $userTypeCode)->first();
    }


    public function findOrFailUserTypeById($userTypeId, $with = [])
    {
        if(!$userType = $this->findUserTypeById($userTypeId,$with)){
            throw new ModelNotFoundException('No Such UserType Found');
        }
        return $userType;
    }

    public function findOrFailUserTypeBySlug($userTypeSlug, $with = [])
    {
        if(!$userType = $this->findUserTypeBySlug($userTypeSlug,$with)){
            throw new ModelNotFoundException('No Such UserType Found');
        }
        return $userType;
    }

    public function findVendorUserType(){
     return $this->findOrFailUserTypeBySlug('vendor');
    }

    public function findAdminUserType(){
        return $this->findOrFailUserTypeBySlug('admin');
    }
    public function findOrFailAdminUserType(){
        return $this->findOrFailUserTypeBySlug('admin');
    }


    public function findStoreUserType(){
        return $this->findOrFailUserTypeBySlug('store');
    }

    public function findSalesManagerUserType(){
        return $this->findOrFailUserTypeBySlug('sales-manager');
    }

    public function findB2CUserType(){
        return $this->findOrFailUserTypeBySlug('b2c-customer');
    }

    public function findOrFailUserTypeByCode($userTypeCode, $with = [])
    {
        if(!$userType = $this->findUserTypeByCode($userTypeCode,$with)){
            throw new ModelNotFoundException('No Such UserType Found');
        }
        return $userType;
    }


    public function storeUserType($validated)
    {
        $authUserCode = getAuthUserCode();
        $userType = new UserType();
        $validated['user_type_code'] = $userType->generateUserTypeCode();
        $validated['slug'] = make_slug($validated['user_type_name']);
        $userType = UserType::create($validated);
        return $userType->fresh();

    }

    public function updateUserType($validated, $userType)
    {
        $validated['slug'] = make_slug($validated['user_type_name']);
        $userType->update($validated);
        return $userType;

    }

    public function delete($userType)
    {
        $userType->delete();
        $userType->save();
        return $userType;
    }



}
