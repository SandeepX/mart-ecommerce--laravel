<?php
namespace App\Modules\Brand\Repositories;

use App\Modules\Brand\Models\BrandFollowersByStore;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BrandFollowersByStoreRepository{

    public function findBrandFollowByStoreCode($brandCode,$storeCode){
        return BrandFollowersByStore::where('brand_code',$brandCode)
            ->where('store_code',$storeCode)->first();
    }
    public function findOrFailBrandFollowByStore($brandCode,$storeCode){
        return BrandFollowersByStore::where('brand_code',$brandCode)
            ->where('store_code',$storeCode)->firstOrFail();
    }
    public function findBrandFollowByStoreWithTrash($brandCode,$storeCode){
        return BrandFollowersByStore::where('brand_code',$brandCode)
            ->where('store_code',$storeCode)->withTrashed()->first();
    }
    public function countBrandFollowByStoreByBrandCode($brandCode){
        return BrandFollowersByStore::where('brand_code',$brandCode)->count();
    }
    public function createBrandFollowByStore($brandCode){
        return BrandFollowersByStore::create(['brand_code'=>$brandCode])->fresh();
    }
    public function updateBrandFollowByStore($brandFollower){
         $brandFollower->delete();
    }
    public function deleteBrandFollowByStore($brandFollower){
         $brandFollower->restore();
    }
}
