<?php

namespace App\Modules\User\Repositories;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\User\Models\UserDoc;
use Exception;

class UserDocRepository
{
    use ImageService;

    public function getUserDocByDocName($docName)
    {
        return UserDoc::where('user_code',getAuthUserCode())
            ->where('doc_name',$docName)
            ->first();
    }

    public function storeDocument($validatedDoc)
    {

        $fileNameToStore='';
        try {
            if($validatedDoc['doc_file']){
                $fileNameToStore = $this->storeImageInServer($validatedDoc['doc_file'], UserDoc::DOCUMENT_PATH);
                $validatedDoc['doc'] = $fileNameToStore;
            }
            return UserDoc::create($validatedDoc);

        }catch (Exception $exception){
            $this->deleteImageFromServer(UserDoc::DOCUMENT_PATH,$fileNameToStore);
            throw $exception;
        }
    }

    public function updateDocument($validatedDoc,$docDetail)
    {

        $updatedfileNameToStore='';
        try {
            if($validatedDoc['doc_file']){
                $this->deleteImageFromServer(UserDoc::DOCUMENT_PATH,$docDetail['doc']);
                $updatedfileNameToStore = $this->storeImageInServer($validatedDoc['doc_file'], UserDoc::DOCUMENT_PATH);
                $validatedDoc['doc'] = $updatedfileNameToStore;

            }
            return $docDetail->update($validatedDoc);

        }catch (Exception $exception){
            $this->deleteImageFromServer(UserDoc::DOCUMENT_PATH,$updatedfileNameToStore);
            throw $exception;
        }
    }


}
