<?php

namespace App\Modules\Brand\Services;

use App\Modules\Brand\Repositories\BrandFollowersByStoreRepository;
use App\Modules\Brand\Repositories\BrandRepository;
use PHPUnit\Exception;

class BrandFollowersByStoreService{
    protected $brandFollowersByStoreRepository;
    protected $brandRepository;

    public function __construct(BrandRepository $brandRepository,BrandFollowersByStoreRepository $brandFollowersByStoreRepository)
    {
        $this->brandFollowersByStoreRepository=$brandFollowersByStoreRepository;
        $this->brandRepository=$brandRepository;
    }

    public function findOrFailBrandFollowByStore($brandCode){
        $storeCode = getAuthStoreCode();
        $brand = $this->brandRepository->findOrFailBrandByCode($brandCode);
        return $this->brandFollowersByStoreRepository->findBrandFollowByStoreCode($brand->brand_code, $storeCode);
        }
    public function countBrandFollowerByStoreByBrandCode($brandCode){
        return $this->brandFollowersByStoreRepository->countBrandFollowByStoreByBrandCode($brandCode);
    }

    public function createOrUpdateBrandFollowByStore($brandCode){
            $brand= $this->brandRepository->findOrFailBrandByCode($brandCode);
             $storeCode=getAuthStoreCode();

            $brandFollower = $this->brandFollowersByStoreRepository
                    ->findBrandFollowByStoreWithTrash($brandCode,$storeCode);

            if(isset($brandFollower) && $brandFollower->deleted_at == NULL){
                $this->brandFollowersByStoreRepository->updateBrandFollowByStore($brandFollower,$brand);
                return ['flag'=>1,];
            }
            if(isset($brandFollower) && $brandFollower->deleted_at)
            {
                $this->brandFollowersByStoreRepository->deleteBrandFollowByStore($brandFollower,$brandCode);
                return ['flag'=>2];
            }
            if(!isset($brandFollower)){
                $this->brandFollowersByStoreRepository->createBrandFollowByStore($brandCode);
                return ['flag'=>3];
            }
        }


}
