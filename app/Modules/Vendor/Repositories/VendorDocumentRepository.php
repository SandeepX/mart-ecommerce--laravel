<?php

namespace App\Modules\Vendor\Repositories;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Vendor\Models\VendorDocument;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VendorDocumentRepository{

    use ImageService;

    public function getAllDocuments($vendor){
        return $vendor->documents;
    }

    public function findDocumentById($documentId){
        return VendorDocument::where('id', $documentId)->first();
    }

    public function findOrFailDocumentById($documentId){
        if($document = $this->findDocumentById($documentId)){
            return $document;
          }
   
        throw new ModelNotFoundException('No Such Document Found !');
    }

    public function storeVendorDocuments($validated, $vendor){
        $authUserCode = getAuthUserCode();
        $data = [];
        $data['created_by'] = $authUserCode;
        $data['updated_by'] = $authUserCode;
        foreach($validated['document_files'] as $key => $document){
            $data['document_file'] = $this->storeImageInServer($document, 'uploads/vendors/documents'); 
            $data['document_name'] = $validated['document_names'][$key];
            $vendor->documents()->create($data);
        }
    }

    public function deleteDocument($document){

        $document->delete();
        $document->deleted_by = getAuthUserCode();
        $document->save();
    }


    public  function getDocumentOfVendor($vendor,$documentId){
        return VendorDocument::where('id', $documentId)->where('vendor_code',$vendor->vendor_code)->first();
    }
}