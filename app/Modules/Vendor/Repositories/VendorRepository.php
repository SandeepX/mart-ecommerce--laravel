<?php

namespace App\Modules\Vendor\Repositories;

use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Vendor\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

use Exception;

class VendorRepository
{

    use ImageService;

    public function getAllVendors($with = []){
        return Vendor::with($with)->latest()->get();
    }

    public function getAllVendorsByActiveStatus($activeStatus,$with=[])
    {
        $vendors= Vendor::with($with);

        if ($activeStatus){
            $vendors = $vendors->active();
        }
        else{
            $vendors= $vendors->notActive();
        }

        return $vendors->latest()->get();
    }

    public function findVendorByCode($vendorCode){
        return Vendor::where('vendor_code', $vendorCode)->first();
    }

    public function findOrFailVendorByCode($vendorCode){
        if($vendor = $this->findVendorByCode($vendorCode)){
          return $vendor;
        }

        throw new ModelNotFoundException('No Such Vendor Found !');

    }

    public function findVendorById($vendorId){
        return Vendor::where('id',$vendorId)->first();
    }

    public function findOrFailVendorById($vendorId){
        if($vendor = $this->findVendorById($vendorId)){
          return $vendor;
        }

        throw new ModelNotFoundException('No Such Vendor Found !');

    }

    public function findVendorBySlug($vendorSlug){
        return Vendor::where('slug',$vendorSlug)->first();
    }

    public function findOrFailVendorBySlug($vendorSlug){
        if($vendor = $this->findVendorBySlug($vendorSlug)){
          return $vendor;
        }

        throw new ModelNotFoundException('No Such Vendor Found !');

    }

    public function create($validated){

        $fileNameToStore='';

        try{
            $vendor = new Vendor;
            $authUserCode = getAuthUserCode();
            $validated['vendor_code'] = $vendor->generateVendorCode();
            $validated['created_by'] = $authUserCode;
            $validated['updated_by'] = $authUserCode;
            $validated['slug'] = makeSlugWithHash($validated['vendor_name']);

            $fileNameToStore = $this->storeImageInServer($validated['vendor_logo'], Vendor::IMAGE_PATH);
            $validated['vendor_logo'] = $fileNameToStore;

            return Vendor::create($validated)->fresh();
        }catch (Exception $exception){
            $this->deleteImageFromServer(Vendor::IMAGE_PATH,$fileNameToStore);
            throw $exception;
        }


    }

    public function update($validated, $vendor){

        $authUserCode = getAuthUserCode();
        $validated['updated_by'] = $authUserCode;
        $validated['slug'] = Str::slug($validated['vendor_name']);

        if(isset($validated['vendor_logo'])){
           $this->deleteImageFromServer(Vendor::IMAGE_PATH, $vendor->vendor_logo);
            $validated['vendor_logo'] = $this->storeImageInServer($validated['vendor_logo'], Vendor::IMAGE_PATH);
        }

        $vendor->update($validated);
        return $vendor->fresh();

    }

    public function delete($vendor) {
        $vendor->delete();
        $vendor->deleted_by = getAuthUserCode();
        $vendor->save();
        return $vendor;
    }


    public function changeVendorStatus(Vendor $vendor,$status)
    {

        try {

            $vendor->is_active = $status;
            $vendor->save();

            return $vendor;
        } catch (Exception $exception) {
            throw $exception;
        }
    }


    public function getWarehouseWiseVendors($warehouseCode,$with=[])
    {
        $vendors= WarehouseProductMaster::select(
            'vendors_detail.vendor_name',
            'vendors_detail.vendor_code'
        )
            ->join('vendors_detail',function($join){
                $join->on('warehouse_product_master.vendor_code','vendors_detail.vendor_code');
            })
            ->where('vendors_detail.is_active',1)
            ->where('warehouse_product_master.warehouse_code',$warehouseCode)
            ->groupBy('vendors_detail.vendor_code')
            ->get();

        return $vendors;
    }

    public function updatePhoneVerificationStatus(Vendor $vendor)
    {
        return $vendor->update([
            'phone_verified_at' => Carbon::now()
        ]);
    }
    public function updateEmailVerificationStatus(Vendor $vendor){
        return $vendor->update([
            'email_verified_at' => Carbon::now()
        ]);
    }
}
