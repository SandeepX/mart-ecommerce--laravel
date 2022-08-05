<?php


namespace App\Modules\AlpasalWarehouse\Repositories\WarehouseStoreGroup;


use App\Modules\AlpasalWarehouse\Models\WhStoreGroup\WarehouseStoreGroupDetail;
use App\Modules\Application\Abstracts\RepositoryAbstract;
use Carbon\Carbon;
use Exception;

class WarehouseStoreGroupDetailRepository extends RepositoryAbstract
{
    public function createMany($validatedArrayData){
        try {
            $groupDetail = new WarehouseStoreGroupDetail();
            $latestPrimaryCode = $groupDetail->generatePrimaryCode();
            // dd($validatedArrayData);
            $validatedArrayData= array_map(function ($validatedData) use ($groupDetail,&$latestPrimaryCode,&$toBeReturnedData){
                $currentDateTime =Carbon::now();
                $validatedData['wh_store_group_detail_code'] = $latestPrimaryCode;
                $validatedData['created_by'] = getAuthUserCode();
                $validatedData['updated_by'] = getAuthUserCode();
                $validatedData['created_at'] = $currentDateTime;
                $validatedData['updated_at'] = $currentDateTime;
                $latestPrimaryCode = $groupDetail->incrementPrimaryCodeWithOutZeroPadding(
                    $latestPrimaryCode,WarehouseStoreGroupDetail::MODEL_PREFIX);

                //only fillables
                $groupDetailArray = array_filter($validatedData, function ($k) use ($groupDetail) {
                    return in_array($k, $groupDetail->getFillables());
                }, ARRAY_FILTER_USE_KEY); //gettin only fillables array

                return $groupDetailArray;

            },$validatedArrayData);
            //dd($validatedArrayData);
            WarehouseStoreGroupDetail::insert($validatedArrayData);
            return $validatedArrayData;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function update(WarehouseStoreGroupDetail $warehouseStoreGroupDetail,$validatedData){
        $warehouseStoreGroupDetail->update($validatedData);
        return $warehouseStoreGroupDetail->fresh();
    }

    public function massDelete(array $groupDetailCodes){
        WarehouseStoreGroupDetail::whereIn('wh_store_group_detail_code',$groupDetailCodes)->delete();
    }
}
