<?php


namespace App\Modules\Brand\Repositories;



use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Brand\Models\Brand;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class BrandRepository
{

  use ImageService;

  public function getAllBrands(){
    return Brand::orderBy('is_featured','desc')->latest()->get();
  }

  public function findBrandById($brandId){
    return Brand::where('id',$brandId)->first();
  }

   public function findOrFailBrandById($brandId){
       if($brand = $this->findBrandById($brandId)){
         return $brand;
       }

       throw new ModelNotFoundException('No Such Brand Found !');

   }

  public function findBrandByCode($brandCode){
        return Brand::where('brand_code',$brandCode)->first();
  }

  public function findOrFailBrandByCode($brandCode){
      if($brand = $this->findBrandByCode($brandCode)){
          return $brand;
      }
      throw new ModelNotFoundException('No Such Brand Found !');

  }

  public function findBrandBySlug($brandSlug){
        return Brand::where('slug',$brandSlug)->first();
  }

  public function findOrFailBrandBySlug($brandSlug){
      if($brand = $this->findBrandBySlug($brandSlug)){
          return $brand;
      }
      throw new ModelNotFoundException('No Such Brand Found !');
  }
  public function brandDetails($brandSlug){
      return Brand::with(['brandSliders'=>function($query){
          $query->where('is_active',1);
      }])->withCount('brandFollowers')->where('slug',$brandSlug)->first();
  }
public function getFeaturedBrands($limit){
      return Brand::where('is_featured',1)->latest()->limit($limit)->get();
}
  public function create($validated){
    //store Image
    $validated['brand_logo'] = $this->storeImageInServer($validated['brand_logo'], 'uploads/brand');
    $validated['slug'] = make_slug($validated['brand_name']);
    return Brand::create($validated)->fresh();

  }

  public function update($validated, $brand){
    //store Image
    if(isset($validated['brand_logo'])){
      $this->deleteImageFromServer('uploads/brand', $brand->brand_logo);
      $validated['brand_logo'] = $this->storeImageInServer($validated['brand_logo'], 'uploads/brand');
    }

    $validated['slug'] = make_slug($validated['brand_name']);
    $brand->update($validated);
    return $brand->fresh();

  }

  public function delete($brand) {
     $brand->delete();
     return $brand;
  }



}
