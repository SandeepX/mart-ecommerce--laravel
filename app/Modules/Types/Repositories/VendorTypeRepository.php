<?php

namespace App\Modules\Types\Repositories;

use App\Modules\Types\Models\VendorType;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VendorTypeRepository
{
    public function getAllVendorTypes(){
        return VendorType::latest()->get();
    }


    public function getAllVendorTypesWithTrashed($with = [])
    {
        return VendorType::with($with)->withTrashed()->latest()->get();
    }

    public function findVendorTypeById($vendorTypeId, $with = [])
    {
        return VendorType::with($with)->where('id', $vendorTypeId)->first();
    }

    public function findVendorTypeBySlug($vendorTypeSlug, $with = [])
    {
        return VendorType::with($with)->where('slug', $vendorTypeSlug)->first();
    }


    public function findVendorTypeByCode($vendorTypeCode, $with = [])
    {
        return VendorType::with($with)->where('Vendor_type_code', $vendorTypeCode)->first();
    }


    public function findOrFailVendorTypeById($vendorTypeId, $with = [])
    {
        if(!$VendorType = $this->findVendorTypeById($vendorTypeId,$with)){
            throw new ModelNotFoundException('No Such VendorType Found');
        }
        return $VendorType;
    }

    public function findOrFailVendorTypeBySlug($vendorTypeSlug, $with = [])
    {
        if(!$VendorType = $this->findVendorTypeBySlug($vendorTypeSlug,$with)){
            throw new ModelNotFoundException('No Such VendorType Found');
        }
        return $VendorType;
    }


    public function findOrFailVendorTypeByCode($vendorTypeCode, $with = [])
    {
        if(!$VendorType = $this->findVendorTypeByCode($vendorTypeCode,$with)){
            throw new ModelNotFoundException('No Such VendorType Found');
        }
        return $VendorType;
    }


    public function storeVendorType($validated)
    {
        $authUserCode = getAuthUserCode();
        $VendorType = new VendorType;
        $validated['vendor_type_code'] = $VendorType->generateVendorTypeCode();
        $validated['slug'] = make_slug($validated['vendor_type_name']);
        $validated['created_by'] = $authUserCode;
        $validated['updated_by'] = $authUserCode;
        $vendorType = VendorType::create($validated);
        return $vendorType->fresh();

    }

    public function updateVendorType($validated, $vendorType)
    {
        $validated['slug'] = make_slug($validated['vendor_type_name']);
        $validated['updated_by'] = getAuthUserCode();
        $vendorType->update($validated);
        return $vendorType;

    }

    public function delete($vendorType)
    {
        $vendorType->delete();
        $vendorType->deleted_by= getAuthUserCode();
        $vendorType->save();
        return $vendorType;
    }





}