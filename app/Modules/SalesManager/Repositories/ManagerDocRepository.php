<?php

namespace App\Modules\SalesManager\Repositories;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\SalesManager\Models\ManagerDoc;
use Exception;

class ManagerDocRepository
{
    use ImageService;

    public function getManagerDocByDocName($docName)
    {
        return ManagerDoc::where('manager_code',getAuthManagerCode())
            ->where('doc_name',$docName)
            ->first();
    }
    public function storeManagerDocument($validatedDoc){
        $fileNameToStore='';
        try {
            if($validatedDoc['doc_file']){
                $fileNameToStore = $this->storeImageInServer($validatedDoc['doc_file'], ManagerDoc::DOCUMENT_PATH);
                $validatedDoc['doc'] = $fileNameToStore;
            }
            return ManagerDoc::create($validatedDoc);

        }catch (Exception $exception){
            $this->deleteImageFromServer(ManagerDoc::DOCUMENT_PATH,$fileNameToStore);
            throw $exception;
        }
    }

    public function updateDocument($validatedDoc,$docDetail)
    {

        $updatedfileNameToStore='';
        try {
            if($validatedDoc['doc_file']){
                $this->deleteImageFromServer(ManagerDoc::DOCUMENT_PATH,$docDetail['doc']);
                $updatedfileNameToStore = $this->storeImageInServer($validatedDoc['doc_file'], ManagerDoc::DOCUMENT_PATH);
                $validatedDoc['doc'] = $updatedfileNameToStore;
            }
            return $docDetail->update($validatedDoc);

        }catch (Exception $exception){
            $this->deleteImageFromServer(ManagerDoc::DOCUMENT_PATH,$updatedfileNameToStore);
            throw $exception;
        }
    }

}
