<?php

namespace App\Modules\Vendor\Services;

use App\Modules\Vendor\Repositories\VendorProductRepository;
use Exception;

class VendorProductService
{

    private $vendorProductRepository;


    public function __construct(VendorProductRepository $vendorProductRepository)
    {
      $this->vendorProductRepository = $vendorProductRepository;
    }


     public function getProductOfVendor($productCode,$authVendorCode,$with=[])
     {
        $vendorProduct = $this->vendorProductRepository->getProductOfVendor($productCode,$authVendorCode,$with);
        if(!$vendorProduct){
            throw new Exception('You are not owner of this product');
        }
        return $vendorProduct;
     }


     public function changeVendorProductTaxability($productCode,$authVendorCode){
       $product = $this->getProductOfVendor($productCode,$authVendorCode);
       $this->vendorProductRepository->updateVendorProductTaxability($product);
       return $product;
     }


    public function changeVendorProductActivation($productCode,$authVendorCode){
        $product = $this->getProductOfVendor($productCode,$authVendorCode);
        $this->vendorProductRepository->updateVendorProductActivation($product);
        return $product;
    }
}
