<?php


namespace App\Modules\PricingLink\Services;


use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\PricingLink\Repositories\PricingMasterRepository;
use App\Modules\PricingLink\Repositories\ProductPricingRepository;
use Illuminate\Support\Facades\DB;

class PricingMasterService
{
    use ImageService;

    private $pricingMasterRepository;

    public function __construct(PricingMasterRepository $pricingMasterRepository)
    {
        $this->pricingMasterRepository = $pricingMasterRepository;
    }

    public function getAllPricingLinks()
    {
        try{
            return $this->pricingMasterRepository->getAllPricingLinks();

        }catch(\Exception $exception){
            throw $exception;
        }
    }

    public function storePricingLink($validatedData)
    {
        try{
            return $this->pricingMasterRepository->storePricingLink($validatedData);

        }catch(\Exception $exception){
            throw $exception;
        }
    }

    public function findPricingLinkByCode($pricingMasterCode)
    {
        try{
            return $this->pricingMasterRepository->findPricingLinkByCode($pricingMasterCode);

        }catch(\Exception $exception){
            throw $exception;
        }
    }




    public function updatePricingMaster($validatedData,$pricingMasterCode)
    {
        try{
            $pricingLink = $this->pricingMasterRepository->findPricingLinkByCode($pricingMasterCode);
            return $this->pricingMasterRepository->updatePricingMaster($validatedData,$pricingLink);

        }catch(\Exception $exception){
            throw $exception;
        }
    }

    public function changePricingLinkStatus($pricingMasterCode)
    {
        DB::beginTransaction();
        try{
            $pricingLink = $this->pricingMasterRepository->findPricingLinkByCode($pricingMasterCode);
            $changePricingLinkStatus = $this->pricingMasterRepository->changePricingLinkStatus($pricingLink);

            DB::commit();
            return $changePricingLinkStatus;

        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}
