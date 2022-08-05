<?php

namespace App\Modules\Vendor\Services;

use App\Modules\User\Repositories\UserRepository;
use App\Modules\Vendor\Repositories\VendorActivityRepository;
use App\Modules\Vendor\Repositories\VendorBannerRepository;
use App\Modules\Vendor\Repositories\VendorRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class VendorActivityService{
    protected $vendorActivityRepository;
    protected $userRepository;
    public function __construct(VendorActivityRepository $vendorActivityRepository)
    {
        $this->vendorActivityRepository = $vendorActivityRepository;

    }
    public function getVendorActivity($userCode,$select,$limit,$startDate,$endDate){
        return $this->vendorActivityRepository->getVendorActivity($userCode,$select,$limit,$startDate,$endDate);
    }



}
