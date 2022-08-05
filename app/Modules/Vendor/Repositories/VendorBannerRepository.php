<?php

namespace App\Modules\Vendor\Repositories;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Vendor\Models\VendorBanner;

class VendorBannerRepository{

    use ImageService;

    public function getAllBanners($vendor){
        return $vendor->banners;
    }

    public function findBannerByName($bannerName){
        return VendorBanner::where('banner', $bannerName)->first();
    }

    public function findOrFailBannerByName($bannerName){
        if($banner = $this->findBannerByName($bannerName)){
            return $banner;
          }
   
        throw new ModelNotFoundException('No Such Banner Found !');
    }

    public function storeVendorBanners($validated, $vendor){
        $authUserCode = getAuthUserCode();
        $validated['created_by'] = $authUserCode;
        $validated['updated_by'] = $authUserCode;
        foreach($validated['banners'] as $banner){
            $validated['banner'] = $this->storeImageInServer($banner, 'uploads/vendors/banners');
            $vendor->banners()->create($validated);
        }
    }

    public function changeBannerStatus($banner){

        $banner->active = !$banner->active;
        $banner->save();
    }

    public function deleteBanner($banner){

        $banner->delete();
        $banner->deleted_by = getAuthUserCode();
        $banner->save();
    }
}