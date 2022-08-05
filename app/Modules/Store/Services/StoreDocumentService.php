<?php

namespace App\Modules\Store\Services;

use App\Modules\Store\Repositories\StoreDocumentRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class StoreDocumentService{
    protected $storeDocumentRepository;
    public function __construct(StoreDocumentRepository $storeDocumentRepository)
    {
        $this->storeDocumentRepository = $storeDocumentRepository;
    }

    public function getAllDocuments($store){
        return $this->storeDocumentRepository->getAllDocuments($store);
    }

    public function storeStoreDocuments($validated, $store){
        DB::beginTransaction();
        try{
            $this->storeDocumentRepository->storeStoreDocuments($validated, $store);
            DB::commit();
        }catch(Exception $exception){
            DB::rollBack();
            throw($exception);
        }
    }

    public function deleteStoreDocument($storeDocumentId){
        DB::beginTransaction();
        try{
            $document = $this->storeDocumentRepository->findOrFailDocumentById($storeDocumentId);
            $this->storeDocumentRepository->deleteDocument($document);
            DB::commit();
        }catch(Exception $exception){
            DB::rollBack();
            throw($exception);
        }
    }


}
