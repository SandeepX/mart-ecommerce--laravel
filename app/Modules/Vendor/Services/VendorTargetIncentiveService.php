<?php


namespace App\Modules\Vendor\Services;

use App\Modules\Vendor\Repositories\VendorTargetIncentiveRepository;
use Exception;

class VendorTargetIncentiveService
{
    private $vendorTargetIncentiveRepository;

    public function __construct(VendorTargetIncentiveRepository $vendorTargetIncentiveRepository)
    {
        $this->vendorTargetIncentiveRepository = $vendorTargetIncentiveRepository;
    }

    public function getVendorTargetIncentativeByVTICode($VTIcode,$with=[])
    {
        return $this->vendorTargetIncentiveRepository->getVendorTargetIncentativeByVTICode($VTIcode,$with=[]);
    }

    public function storeVendorTargetIncentiveDetail($validatedData)
    {
        try{
            $data = $this->vendorTargetIncentiveRepository->store($validatedData);
            return $data;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function updateVendorTargetIncentativeDetail($validatedData,$VTIcode)
    {
        try{
            $vendorTargetIncentativeDetail = $this->vendorTargetIncentiveRepository->getVendorTargetIncentativeByVTICode($VTIcode);
            $validatedData['updated_by'] = getAuthUserCode();
            $this->vendorTargetIncentiveRepository->update($vendorTargetIncentativeDetail,$validatedData);
            return $validatedData;

        }catch(Exception $exception){
            return $exception;
        }
    }

    public function delete($VTIcode)
    {
        try{
            $vendorTargetIncentativeDetail = $this->vendorTargetIncentiveRepository->getVendorTargetIncentativeByVTICode($VTIcode);
            return $this->vendorTargetIncentiveRepository->delete($vendorTargetIncentativeDetail);
        }catch(Exception $exception){
            return $exception;
        }
    }

}

