<?php

namespace App\Modules\Store\Repositories;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Store\Models\StoreDocument;

class StoreDocumentRepository{

    use ImageService;

    public function getAllDocuments($store){
        return $store->documents;
    }

    public function findDocumentById($documentId){
        return StoreDocument::where('id', $documentId)->first();
    }

    public function findOrFailDocumentById($documentId){
        if($document = $this->findDocumentById($documentId)){
            return $document;
          }

        throw new ModelNotFoundException('No Such Document Found !');
    }

    public function storeStoreDocuments($validated, $store){
        $authUserCode = getAuthUserCode();
        $data = [];
        $data['created_by'] = $authUserCode;
        $data['updated_by'] = $authUserCode;
        foreach($validated['document_files'] as $key => $document){
            $data['document_file'] = $this->storeImageInServer($document, 'uploads/stores/documents');
            $data['document_name'] = $validated['document_names'][$key];
            $store->documents()->create($data);
        }
    }


    public function deleteDocument($document){

        $document->delete();
        $document->deleted_by = getAuthUserCode();
        $document->save();
    }
}
