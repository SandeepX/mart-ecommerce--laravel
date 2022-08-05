<?php

namespace App\Modules\Lead\Services;

use App\Modules\Lead\Repositories\LeadRepository;
use Illuminate\Support\Facades\DB;

class LeadService
{
    private $leadRepository;

    public function __construct(LeadRepository $leadRepository){
       $this->leadRepository = $leadRepository;
    }


    public function getAllLeads(){
        return $this->leadRepository->getAllLeads();
    }



    public function findLeadByCode($leadCode){
        return $this->leadRepository->findLeadByCode($leadCode);
    }

    public function findLeadByID($leadID){
        return $this->leadRepository->findLeadByID($leadID);
    }

    public function findLeadBySlug($leadSlug){
        return $this->leadRepository->findLeadBySlug($leadSlug);
    }

    public function findOrFailLeadById($leadId)
    {
        return $this->leadRepository->findOrFailLeadById($leadId);
    }


    public function findOrFailLeadByCode($leadCode)
    {
        return $this->leadRepository->findOrFailLeadByCode($leadCode);
    }

    public function findOrFailLeadBySlug($leadSlug)
    {
        return $this->leadRepository->findOrFailLeadBySlug($leadSlug);
    }


    public function storeLeadDetails($validated){
        DB::beginTransaction();
        try {

            $lead = $this->leadRepository->create($validated);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $lead;
    }


    public function updateLeadDetails($validated, $leadCode)
    {
        DB::beginTransaction();

        try {
            $lead = $this->findLeadByCode($leadCode);
            $lead = $this->leadRepository->update($validated, $lead);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $lead;
    }

    public function deleteLeadDetails($leadCode)
    {
        DB::beginTransaction();
        try {
            $lead = $this->findLeadByCode($leadCode);
            $lead = $this->leadRepository->delete($lead);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $lead;
    }

}
