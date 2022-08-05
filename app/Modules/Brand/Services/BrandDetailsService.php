<?php


namespace App\Modules\Brand\Services;

use DB;
use Exception;


class BrandDetailsService
{
    protected $brandService;
    protected $brandSliderService;
    protected $brandFollowersByStoreService;
    public function __construct(BrandService $brandService,
                                BrandSliderService $brandSliderService,
                                BrandFollowersByStoreService $brandFollowersByStoreService){
        $this->brandService=$brandService;
        $this->brandSliderService=$brandSliderService;
        $this->brandFollowersByStoreService=$brandFollowersByStoreService;

    }
    public function brandDetailsBySlug($brandSlug){
        try{
            $brand=$this->brandService->findOrFailBrandBySlug($brandSlug);
            $brandSlider=$this->brandSliderService->getAllActiveBrandSliderByBrandSlug($brandSlug);
            $brandFollower=$this->brandFollowersByStoreService->countBrandFollowerByStoreByBrandCode($brand->brand_code);
            $brandDetails['brand']=$brand;
            $brandDetails['slider']=$brandSlider;
            $brandDetails['follower']=$brandFollower;
            return $brandDetails;
        }catch(\Exception $exception){

        }

    }




}
