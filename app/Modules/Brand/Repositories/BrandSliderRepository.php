<?php
namespace App\Modules\Brand\Repositories;

use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Brand\Models\BrandSlider;

class BrandSliderRepository extends RepositoryAbstract
{
    use ImageService;
    public function findOrFailBrandSliderByCode($brandSliderCode){
        return BrandSlider::where('brand_slider_code',$brandSliderCode)->firstOrFail();
    }
    public function getAllActiveBrandSlider(){
        return BrandSlider::select($this->select)->where('is_active',1)->latest()->get();
    }
    public function getAllBrandSlider($brand){
       return BrandSlider::where('brand_code',$brand->brand_code)->latest()->get();
    }
    public function getAllActiveBrandSliderByCode($brandCode){
       return BrandSlider::select($this->select)->where('brand_code',$brandCode)->where('is_active',1)->latest()->get();

    }
    public function createBrandSlider($validatedData){
        if(isset($validatedData['image'])){
            $validatedData['image']=$this->storeImageInServer($validatedData['image'],BrandSlider::BRAND_SLIDER_IMAGE_PATH);
        }
        BrandSlider::create($validatedData);
    }
    public function updateBrandSlider($validatedData,$brandSlider){

        if(isset($validatedData['image'])){
            $this->deleteImageFromServer(BrandSlider::BRAND_SLIDER_IMAGE_PATH,$brandSlider->image);
            $validatedData['image']=$this->storeImageInServer($validatedData['image'],BrandSlider::BRAND_SLIDER_IMAGE_PATH);
        }
        $brandSlider->update($validatedData);
    }
    public function deleteBrandSlider($brandSlider){
        $brandSlider->delete();
    }
}
