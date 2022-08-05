<?php

namespace App\Modules\OfflinePayment\Repositories;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\OfflinePayment\Models\OfflinePaymentDoc;
use App\Modules\OfflinePayment\Models\OfflinePaymentMaster;
use Exception;

class OfflinePaymentDocumentRepository
{
    use ImageService;
    public function savePaymentDocument(OfflinePaymentMaster $offlinePayment,$document,$documentType){
        $fileNameToStore='';
        try{
            $fileNameToStore = $this->storeImageInServer($document, OfflinePaymentDoc::UPLOAD_PATH);
            $offlinePayment->paymentDocuments()->create([
                'file_name' => $fileNameToStore,
                'document_type' => $documentType
            ]);
        }catch (Exception $e){
            $this->deleteImageFromServer(OfflinePaymentDoc::UPLOAD_PATH,$fileNameToStore);
            throw $e;
        }
    }

}
