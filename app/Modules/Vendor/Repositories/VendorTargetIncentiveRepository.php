<?php

namespace App\Modules\Vendor\Repositories;

use App\Modules\Vendor\Models\VendorTargetIncentive;
use Illuminate\Support\Facades\DB;
use Exception;

class VendorTargetIncentiveRepository
{

    public function getVendorTargetIncentativeByVTICode($VTIcode,$with=[])
    {
        try{
            return VendorTargetIncentive::with($with)
                ->where('vendor_target_incentive_code',$VTIcode)
                ->firstorFail();
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function store($validatedData)
    {
        try{
            return VendorTargetIncentive::create($validatedData)->fresh();
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function update($vendorTargetIncentativeDetail,$validatedData)
    {
        try{
            return $vendorTargetIncentativeDetail->update($validatedData);
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function delete($vendorTargetIncentativeDetail)
    {
        try{
          return $vendorTargetIncentativeDetail->delete();

        } catch(Exception $exception){
          throw $exception;
        }
    }

    public function getVendorTargetIncentativeByVTMCode($VTMcode)
    {

        try{
            $results = DB::table('vendor_target_incentive')
                ->join('products_master','products_master.product_code', '=', 'vendor_target_incentive.product_code')
                ->join('product_variants','product_variants.product_variant_code', '=', 'vendor_target_incentive.product_variant_code')
                ->where('vendor_target_incentive.vendor_target_master_code',$VTMcode)

                ->select(
                    'vendor_target_incentive.vendor_target_incentive_code',
                    'vendor_target_incentive.vendor_target_master_code',
                    'vendor_target_incentive.product_code',
                    'vendor_target_incentive.product_variant_code',
                    'vendor_target_incentive.starting_range',
                    'vendor_target_incentive.end_range',
                    'vendor_target_incentive.incentive_type',
                    'vendor_target_incentive.incentive_value',
                    'vendor_target_incentive.has_meet_target',
                    'vendor_target_incentive.created_by',
                    'vendor_target_incentive.updated_by',
                    'products_master.product_name',
                    'product_variants.product_variant_name'
                )
                 ->paginate(10);
            return $results;
        }catch(Exception $exception){
            throw $exception;
        }
    }

//    public function getLocationDetail($table)
//    {
//        try{
//            $data = DB::table($table)->where('location_type','province')->get();
//            return $data;
//        }catch(Exception $exception){
//            throw $exception;
//        }
//    }
//
//    public function getAllProvince($table)
//    {
//        try{
//            $data = DB::table($table)->where('location_type','province')->get();
//            return $data;
//        }catch(Exception $exception){
//            throw $exception;
//        }
//    }
//
//    public function getAllDistrict($table,$provinceCode)
//    {
//        try{
//            $data = DB::table($table)->where('upper_location_code',$provinceCode)
//                ->where('location_type','district')
//                ->get();
//            return $data;
//        }catch(Exception $exception){
//            throw $exception;
//        }
//    }
//
//    public function getAllMunicipality($table,$districtCode)
//    {
//        try{
//            $data = DB::table($table)->where('upper_location_code',$districtCode)
//                ->where('location_type','municipality')
//                ->get();
//            return $data;
//        }catch(Exception $exception){
//            throw $exception;
//        }
//    }


}
