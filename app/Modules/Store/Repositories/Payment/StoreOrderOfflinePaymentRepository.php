<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/1/2020
 * Time: 1:25 PM
 */

namespace App\Modules\Store\Repositories\Payment;


use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Store\Models\Payments\StoreOrderOfflinePayment;

use App\Modules\Store\Models\Payments\StoreOrderOfflinePaymentDocument;
use Carbon\Carbon;
use Exception;

class StoreOrderOfflinePaymentRepository
{
    use ImageService;

    public function getAllPaginatedByStoreCodeWith($storeCode,$paginateBy,array $with){

        return StoreOrderOfflinePayment::with($with)->where('store_code',$storeCode)->latest()->paginate($paginateBy);
    }



    public function getPaymentsByStoreOrderCode($storeCode,$paginateBy,array $with,$storeOrderCode){

        return StoreOrderOfflinePayment::with($with)
        ->where('store_code',$storeCode)
        ->where('store_order_code',$storeOrderCode)
        ->latest()->paginate($paginateBy);
    }

    public function findOrFailByCodeOfStore($storeOfflinePaymentCode,$storeCode,$with=[]){

        return StoreOrderOfflinePayment::with($with)->where('store_offline_payment_code',$storeOfflinePaymentCode)
            ->where('store_code',$storeCode)->firstOrFail();
    }

    public function findOrFailByCode($storeOfflinePaymentCode,$with=[]){

        return StoreOrderOfflinePayment::with($with)->where('store_offline_payment_code',$storeOfflinePaymentCode)->firstOrFail();
    }

    public function updatePaymentStatus(StoreOrderOfflinePayment $offlinePayment,$validatedData){

        $offlinePayment->payment_status = $validatedData['payment_status'];
        $offlinePayment->remarks = $validatedData['remarks'];
        $offlinePayment->responded_by = getAuthUserCode();
        $offlinePayment->responded_at = Carbon::now();

        $offlinePayment->save();

        return $offlinePayment;
    }

    public function save($validatedData){
        $validatedData['user_code'] = getAuthUserCode();

        return StoreOrderOfflinePayment::create($validatedData)->fresh();

    }

    public function savePaymentMetaDetail(StoreOrderOfflinePayment $offlinePayment,$metaDetails){

        try{
           $offlinePayment->paymentMetaData()->delete();
           $offlinePayment->paymentMetaData()->createMany($metaDetails);

        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function savePaymentDocument(StoreOrderOfflinePayment $offlinePayment,$document,$documentType){

        $fileNameToStore='';
        try{

            //dd($miscellaneousPayment->paymentDocuments);
            $fileNameToStore = $this->storeImageInServer($document, StoreOrderOfflinePaymentDocument::UPLOAD_PATH);

            $offlinePayment->paymentDocuments()->create([
                'file_name' => $fileNameToStore,
                'document_type' => $documentType

            ]);

        }catch (Exception $e){

            $this->deleteImageFromServer(StoreOrderOfflinePaymentDocument::UPLOAD_PATH,$fileNameToStore);
            throw $e;
        }
    }

    public function deletePaymentDocuments(StoreOrderOfflinePayment $offlinePayment,array $deletedDocumentCodes){

        try{
            $toBeDeletedDocs=$offlinePayment->paymentDocuments()->whereIn('payment_doc_code',$deletedDocumentCodes);
            $toBeDeletedFiles=$toBeDeletedDocs->pluck('file_name')->toArray();

           // dd($toBeDeletedFiles);
            $toBeDeletedDocs->delete();
            foreach ($toBeDeletedFiles as $deletedFile){
                $this->deleteImageFromServer(StoreOrderOfflinePaymentDocument::UPLOAD_PATH,$deletedFile);
            }
        }catch (Exception $exception){
            throw $exception;
        }
    }
}