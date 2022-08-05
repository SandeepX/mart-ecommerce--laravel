<?php

namespace App\Modules\Store\Repositories\StorePackageTypes;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Store\Models\StorePackageTypes\StoreTypePackageHistory;
use App\Modules\Store\Models\StorePackageTypes\StoreTypePackageMaster;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

use Exception;

class StoreTypePackageHistoryRepository
{

    use ImageService;

    public function findorFailByStoreTypePackageHistoryCode($storeTypePackageHistoryCode){
        $storeTypePackageHistory = StoreTypePackageHistory::where('store_type_package_history_code',$storeTypePackageHistoryCode)
            ->first();
        if(!$storeTypePackageHistory){
            throw new Exception('Store type package history not found');
        }
        return $storeTypePackageHistory;
    }

    public function findLatestHistoryRowByMasterCode($storeTPMCode)
    {
        return StoreTypePackageHistory::where('store_type_package_master_code', $storeTPMCode)
            ->whereNull('to_date')
            ->latest()->first();
    }

    public function createStoreTypePackageHistory(StoreTypePackageMaster $storePackageMaster)
    {

        try {
            $currentDate = Carbon::now();
            $validatedData = [
                'store_type_package_master_code' => $storePackageMaster->store_type_package_master_code,
                'store_type_code' => $storePackageMaster->store_type_code,
                'package_name' => $storePackageMaster->package_name,
                'package_slug' => $storePackageMaster->package_slug,
                'description' => $storePackageMaster->description,
                //'image' => $storePackageMaster->image,
                'refundable_registration_charge' => $storePackageMaster->refundable_registration_charge,
                'non_refundable_registration_charge' => $storePackageMaster->non_refundable_registration_charge,
                'base_investment' => $storePackageMaster->base_investment,
                'annual_purchasing_limit' => $storePackageMaster->annual_purchasing_limit,
                'referal_registration_incentive_amount' => $storePackageMaster->referal_registration_incentive_amount,
                'from_date' => $currentDate,
                'created_by' => $storePackageMaster->created_by,
                'updated_by' => $storePackageMaster->updated_by
            ];

//            $masterImageFile  =  public_path(StoreTypePackageMaster::IMAGE_PATH).$storePackageMaster->image;
//            $historyImageFile =  public_path(StoreTypePackageHistory::IMAGE_PATH).$storePackageMaster->image;
//
//            copy($masterImageFile,$historyImageFile);

            StoreTypePackageHistory::create($validatedData)->fresh();
        } catch (Exception $e) {
            //$this->deleteImageFromServer(StoreTypePackageHistory::IMAGE_PATH, $storePackageMaster->image);
            throw $e;
        }
    }


    public function updateLatestHistoryRow($latestHistoryRow)
    {

        try {
            $latestHistoryRow->to_date = Carbon::now();
            $latestHistoryRow->save();
            return $latestHistoryRow->fresh();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getStoreTypePackageOfStoreType($storeTypeCode)
    {
       // dd($storeTypeCode);
        $storeTypePackages = StoreTypePackageHistory::whereHas('storeType', function ($query) {
              $query->where('is_active',1);
        })
            ->join('store_type_package_master',
                'store_type_package_master.store_type_package_master_code',
                '=',
                'store_type_package_history.store_type_package_master_code'
            )
            ->where('store_type_package_history.store_type_code', $storeTypeCode)
            ->whereNull('store_type_package_history.to_date')
            ->where('store_type_package_history.is_active', 1)
            ->select('store_type_package_history.*')
            ->orderBy('store_type_package_master.sort_order')
            ->get();
        return $storeTypePackages;
    }

    public function changeStoreTypePackageHistoryStatus(StoreTypePackageHistory $storeTypePackageHistory,$status)
    {

        try {

            $storeTypePackageHistory->is_active = $status;
            $storeTypePackageHistory->save();

            return $storeTypePackageHistory;
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
