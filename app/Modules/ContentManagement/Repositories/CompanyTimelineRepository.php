<?php

namespace App\Modules\ContentManagement\Repositories;

use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\ContentManagement\Models\CompanyTimeline;

class CompanyTimelineRepository extends RepositoryAbstract
{

    public function findOrFailCompanyTimelineByCode($companyTimelineCode)
    {
        return CompanyTimeline::where('company_timeline_code',$companyTimelineCode)->firstOrFail();
    }

    public function getAllCompanyTimeline()
    {
        return CompanyTimeline::orderBy('is_active', 'desc')->latest()->get();
    }
    public function getLatestActiveCompanyTimeline(){
        return CompanyTimeline::with($this->with)->select($this->select)->where('is_active',1)->orderBy($this->orderByColumn)->get();
    }

    public function storeCompanyTimeline($validatedData)
    {
        CompanyTimeline::create($validatedData);
    }

    public function updateCompanyTimeline($validatedData, $companyTimeline)
    {
        $companyTimeline->update($validatedData);
    }
    public function deleteCompanyTimeline($companyTimeline)
    {
        $companyTimeline->delete();
    }
}
