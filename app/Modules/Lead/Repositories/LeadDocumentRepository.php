<?php

namespace App\Modules\Lead\Repositories;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Lead\Models\LeadDocument;

class LeadDocumentRepository
{

    use ImageService;

    private $leadDocument;

    public function __construct(LeadDocument $leadDocument)
    {
        $this->leadDocument = $leadDocument;
    }

    public function getAllDocuments($lead)
    {
        return $lead->documents;
    }

    public function getDocumentOfLead($leadCode, $leadDocumentID)
    {
        return $this->leadDocument->whereHas('lead', function ($query) use ($leadCode) {
            $query->where('lead_code', $leadCode);
        })->where('id',$leadDocumentID)->first();
    }

    public function getDocumentTypeEnums()
    {
        return $this->leadDocument->getLeadDocumentEnums();
    }


    public function getDocumentTypeOptions()
    {
        return $this->leadDocument->getLeadDocumentOptions();
    }


    public function findDocumentById($documentId)
    {
        return $this->leadDocument->where('id', $documentId)->first();
    }

    public function findOrFailDocumentById($documentId)
    {
        if ($document = $this->findDocumentById($documentId)) {
            return $document;
        }

        throw new ModelNotFoundException('No Such Document Found !');
    }



    public function storeLeadDocuments($validated, $lead)
    {
        $data = [];
        foreach ($validated['document_files'] as $key => $document) {
            $data['document_file'] = $this->storeImageInServer($document, 'uploads/lead/documents');
            $data['document_type'] = $validated['document_types'][$key];
            $lead->documents()->create($data);
        }
    }

    
    public function deleteDocument(LeadDocument $document)
    {
        $document->delete();
        return $document;
    }
}
