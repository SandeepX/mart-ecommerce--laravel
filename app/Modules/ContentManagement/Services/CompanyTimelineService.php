<?php

namespace App\Modules\ContentManagement\Services;

use App\Modules\ContentManagement\Repositories\CompanyTimelineRepository;
use Illuminate\Support\Facades\DB;

class CompanyTimelineService
{
    private $companyTimelineRepository;
    public function __construct(CompanyTimelineRepository $companyTimelineRepository)
    {
        $this->companyTimelineRepository = $companyTimelineRepository;
    }

    public function findOrFailCompanyTimelineByCode($companyTimelineCode)
    {
        return $this->companyTimelineRepository->findOrFailCompanyTimelineByCode($companyTimelineCode);
    }

    public function getAllCompanyTimeline()
    {
        return $this->companyTimelineRepository->getAllCompanyTimeline();
    }
    public function getLatestActiveCompanyTimeline($select=['*'],$with=[],$orderBy='year'){
        return $this->companyTimelineRepository->select($select)->with($with)->orderBy($orderBy)->getLatestActiveCompanyTimeline();
    }

    public function storeCompanyTimeline($validatedData)
    {
        try{
            DB::beginTransaction();
            $this->companyTimelineRepository->storeCompanyTimeline($validatedData);
            DB::commit();
        }
        catch(\Exception $exception){
            DB::rollback();
            throw $exception;
        }

    }

    public function updateCompanyTimeline($validatedData, $companyTimeline)
    {
        if(!isset($validatedData['is_active']))
            $validatedData['is_active'] = 0;
        $companyTimeline = $this->companyTimelineRepository->findOrFailCompanyTimelineByCode($companyTimeline);
        $this->companyTimelineRepository->updateCompanyTimeline($validatedData, $companyTimeline);
    }

    public function deleteVisionMission($companyTimeline)
    {
//        $about=$this->aboutUsRepository->getAllLatestActiveAboutUs()->count();
        $companyTimeline = $this->companyTimelineRepository->findOrFailCompanyTimelineByCode($companyTimeline);
        $this->companyTimelineRepository->deleteCompanyTimeline($companyTimeline);


    }
}
