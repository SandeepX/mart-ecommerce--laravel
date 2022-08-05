<?php

namespace App\Modules\Lead\Services;

use App\Modules\Lead\Repositories\LeadDocumentRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class LeadDocumentService
{
    protected $leadDocumentRepository;
    public function __construct(LeadDocumentRepository $leadDocumentRepository)
    {
        $this->leadDocumentRepository = $leadDocumentRepository;
    }

    public function getAllDocuments($lead)
    {
        return $this->leadDocumentRepository->getAllDocuments($lead);
    }

    public function getDocumentOfLead($leadCode, $leadDocumentID)
    {
        return  $this->leadDocumentRepository->getDocumentOfLead($leadCode, $leadDocumentID);
    }

    public function getDocumentTypeEnums()
    {
        return $this->leadDocumentRepository->getDocumentTypeEnums();
    }


    public function getDocumentTypeOptions()
    {
        return $this->leadDocumentRepository->getDocumentTypeOptions();
    }




    public function storeLeadDocuments($validated, $lead)
    {
        DB::beginTransaction();
        try {
            $this->leadDocumentRepository->storeLeadDocuments($validated, $lead);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw ($exception);
        }
    }

    public function deleteLeadDocument($leadCode,$leadDocumentId)
    {
        DB::beginTransaction();
        try {
            $leadDocument = $this->getDocumentOfLead($leadCode,$leadDocumentId);
            $this->leadDocumentRepository->deleteDocument($leadDocument);
            DB::commit();
            return $leadDocument;
        } catch (Exception $exception) {
            DB::rollBack();
            throw ($exception);
        }
    }
}
