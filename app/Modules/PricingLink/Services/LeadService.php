<?php


namespace App\Modules\PricingLink\Services;


use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\PricingLink\Repositories\LeadRepository;
use Illuminate\Support\Facades\DB;

class LeadService
{
    use ImageService;

    private $leadRepository;

    public function __construct(LeadRepository $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }

    public function getAllPricingLinkLeads($filterParameters)
    {
        try{
            return $this->leadRepository->getAllPricingLinkLeads($filterParameters);

        }catch(\Exception $exception){
            throw $exception;
        }
    }
}
