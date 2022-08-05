<?php
 namespace App\Modules\Brand\Services;

use App\Modules\Brand\Repositories\BrandRepository;
use App\Modules\Brand\Repositories\BrandSliderRepository;
use Illuminate\Support\Facades\DB;

class BrandSliderService{

    protected $brandSliderRepository;
    protected $brandRepository;
    public function __construct(BrandSliderRepository $brandSliderRepository,BrandRepository $brandRepository){
        $this->brandSliderRepository=$brandSliderRepository;
        $this->brandRepository=$brandRepository;
    }
    public function getAllBrandSlider($brand){
        return $this->brandSliderRepository->getAllBrandSlider($brand);
    }
    public function findOrFailBrandSliderByCode($brandSliderCode){
        return $this->brandSliderRepository->findOrFailBrandSliderByCode($brandSliderCode);
    }
    //it is used only for api
//    public function getAllActiveBrandSliderByCode($brandCode,$select=['*']){
//
//        return $this->brandSliderRepository->select($select)->getAllActiveBrandSliderByCode($brandCode);
//    }
    public function getAllActiveBrandSliderByBrandSlug($brandSlug,$select=['*']){
        $brand=$this->brandRepository->findOrFailBrandBySlug($brandSlug);
        return $this->brandSliderRepository->select($select)->getAllActiveBrandSliderByCode($brand->brand_code);
    }
    public function createBrandSlider($validatedData){
        try{
            DB::beginTransaction();
            $this->brandSliderRepository->createBrandSlider($validatedData);
            DB::commit();

        }catch(\Exception $exception){
            DB::rollBack();
            throw($exception);
        }
    }
    public function updateBrandSlider($validatedData,$brandSliderCode){
        if(!isset($validatedData['is_active']))
            $validatedData['is_active']=0;

        try{
            DB::beginTransaction();
            $brandSlider= $this->brandSliderRepository->findOrFailBrandSliderByCode($brandSliderCode);
            $this->brandSliderRepository->updateBrandSlider($validatedData,$brandSlider);
            DB::commit();
        }catch(\Exception $exception){
            DB::rollBack();
            throw($exception);
        }
    }
    public function deleteBrandSlider($brandsliderCode){
        try{
            $brandSlider=$this->brandSliderRepository->findOrFailBrandSliderByCode($brandsliderCode);
            $this->brandSliderRepository->deleteBrandSlider($brandSlider);
        }catch(\Exception $exception){
            throw $exception;
        }
    }
}
