<?php

namespace App\Modules\Store\Repositories\StorePackageTypes;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Store\Models\StorePackageTypes\StoreTypePackageMaster;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Exception;

class StoreTypePackageRepository
{

    use ImageService;
    public function getAllStoreTypePackages($storeTypeCode,$with=[])
    {
        return StoreTypePackageMaster::where('store_type_code',$storeTypeCode)
        ->with($with)->orderBy('sort_order','ASC')->get();
    }

    public function findStoreTypePackageByCode($storeTPMCode)
    {
        return StoreTypePackageMaster::where('store_type_package_master_code', $storeTPMCode)->first();
    }

    public function findOrFailStoreTypePackageByCode($storeTPMCode,$with=[],$select='*')
    {
        return StoreTypePackageMaster::with($with)->select($select)->where('store_type_package_master_code', $storeTPMCode)->firstOrFail();
    }


    public function createStoreTypePackage($validatedData)
    {

        try {
            //handle Image
           // $validatedData['image'] = $this->storeImageInServer($validatedData['image'], StoreTypePackageMaster::IMAGE_PATH);
            $validatedData['package_slug'] = makeSlugWithHash($validatedData['package_name']);

            return StoreTypePackageMaster::create($validatedData);
        } catch (Exception $e) {
           // $this->deleteImageFromServer(StoreTypePackageMaster::IMAGE_PATH, $validatedData['image']);
            throw $e;
        }
    }


    public function updateStoreTypePackage($validatedData, $storeTypePackage)
    {

        try {

            $validatedData['package_slug'] = makeSlugWithHash($validatedData['package_name']);
            if(isset($validatedData['image'])){
                $this->deleteImageFromServer(StoreTypePackageMaster::IMAGE_PATH, $storeTypePackage->image);
                $validatedData['image'] = $this->storeImageInServer($validatedData['image'], StoreTypePackageMaster::IMAGE_PATH);
            }

            $storeTypePackage->update($validatedData);
            return $storeTypePackage->fresh();
        } catch (Exception $e) {
            $this->deleteImageFromServer(StoreTypePackageMaster::IMAGE_PATH, $validatedData['image']);
            throw $e;
        }
    }

    public function delete($storeTypePackage)
    {
        $storeTypePackage->delete();
        return $storeTypePackage;
    }

    public function changeStoreTypePackageStatus(StoreTypePackageMaster $storeTypePackage,$status){

        try{

            $storeTypePackage->is_active = $status;
            $storeTypePackage->save();

            return $storeTypePackage;
        }catch (Exception $exception){
            throw $exception;
        }

    }

}
