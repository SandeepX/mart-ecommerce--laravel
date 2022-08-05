<?php

namespace App\Modules\Vendor\Repositories;

use App\Modules\Vendor\Models\VendorTargetMaster;
use Carbon\Carbon;
use Exception;


class VendorTargetRepository
{

    public function getVendorTargetVTMByCode($VTMCode,$with=[])
    {
        try{
            $vendorCode = getAuthVendorCode();
            $vendorTargetDetail = VendorTargetMaster::with($with)->where('Vendor_target_master_code',$VTMCode)
                                               ->where('vendor_code',$vendorCode)
                                                ->firstorFail();
            return $vendorTargetDetail;
        }catch(Exception $e){
            return $e;
        }
    }

    public function store($validatedData)
    {
        try{
            return VendorTargetMaster::create($validatedData);
        }catch (Exception $exception){
            return $exception;
        }
    }

    public function update($vendorTargetDetail,$validatedData)
    {
        try{
            return $vendorTargetDetail->update($validatedData);
        }catch(Exception $exception){
           return $exception;
        }
    }

    public function delete($vendorTargetData)
    {
        try{
            return $vendorTargetData->delete();

        }catch(Exception $exception){
            return $exception;
        }
    }

    public function getVendorTargetDetailForAdmin($VTMcode,$with=[])
    {
        try{
            $vendorTargetDetail = VendorTargetMaster::with($with)->where('Vendor_target_master_code',$VTMcode)
                                ->firstorFail();
            return $vendorTargetDetail;
        }catch(Exception $e){
            return $e;
        }
    }

    public function updateStatus($vendorTargetDetail,$status)
    {
        try{
            return $vendorTargetDetail->update([
                'is_active' =>  $status
                ]);
        }catch(Exception $e){
            throw $e;
        }
    }

    public function updateVendorTargetStatus($vendorTargetDetail,$status)
    {
        try{
            return $vendorTargetDetail->update([
                'status' =>  $status
            ]);
        }catch(Exception $e){
            throw $e;
        }
    }

    Public function getVendorTargetBasedOnLocation($locationCode)
    {
        try{
           $vendorTargetsByLocationCode = VendorTargetMaster::where('is_active',1)
               ->where('status','accepted')
               ->where('start_date','<=',Carbon::now())
               ->where('end_date','>=',Carbon::now())
               ->where('municipality_code',$locationCode)
//               ->where(function($query) use ($locationCode) {
//                   $query->where('district_code', $locationCode)
//                         ->orWhere('municipality_code', $locationCode)
//                         ->orWhere('province_code', $locationCode);
//                    })
               ->get();

           return $vendorTargetsByLocationCode;

        }catch (Exception $exception){
            throw $exception;
        }
    }
}
