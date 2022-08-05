<?php

namespace App\Modules\Vendor\Services;

use App\Modules\Vendor\Repositories\VendorBannerRepository;
use App\Modules\Vendor\Repositories\VendorRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class VendorBannerService{
    protected $vendorBannerRepository;
    public function __construct(VendorBannerRepository $vendorBannerRepository)
    {
        $this->vendorBannerRepository = $vendorBannerRepository;
    }

    public function getAllBanners($vendor){
        return $this->vendorBannerRepository->getAllBanners($vendor);
    }

    public function storeVendorBanners($validated, $vendor){
        DB::beginTransaction();
        try{
            $this->vendorBannerRepository->storeVendorBanners($validated, $vendor);
            DB::commit();
        }catch(Exception $exception){
            DB::rollBack();
            throw($exception);
        }
    }

    public function changeBannerStatus($vendorBannerName){
        DB::beginTransaction();
        try{
            $banner = $this->vendorBannerRepository->findOrFailBannerByName($vendorBannerName);
            $this->vendorBannerRepository->changeBannerStatus($banner);
            DB::commit();
        }catch(Exception $exception){
            DB::rollBack();
            throw($exception);
        }
    }

    public function deleteVendorBanner($vendorBannerName){
        DB::beginTransaction();
        try{
            $banner = $this->vendorBannerRepository->findOrFailBannerByName($vendorBannerName);
            $this->vendorBannerRepository->deleteBanner($banner);
            DB::commit();
        }catch(Exception $exception){
            DB::rollBack();
            throw($exception);
        }
    }


}