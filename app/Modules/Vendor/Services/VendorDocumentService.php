<?php

namespace App\Modules\Vendor\Services;

use App\Modules\Vendor\Repositories\VendorDocumentRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class VendorDocumentService{
    protected $vendorDocumentRepository;
    public function __construct(VendorDocumentRepository $vendorDocumentRepository)
    {
        $this->vendorDocumentRepository = $vendorDocumentRepository;
    }

    public function getAllDocuments($vendor){
        return $this->vendorDocumentRepository->getAllDocuments($vendor);
    }

    public function storeVendorDocuments($validated, $vendor){
        DB::beginTransaction();
        try{
            $this->vendorDocumentRepository->storeVendorDocuments($validated, $vendor);
            DB::commit();
        }catch(Exception $exception){
            DB::rollBack();
            throw($exception);
        }
    }

    public function deleteVendorDocument($vendor,$vendorDocumentId){
        DB::beginTransaction();
        try{
            $document = $this->vendorDocumentRepository->getDocumentOfVendor($vendor,$vendorDocumentId);
            if(!$document){
                throw new Exception('Cannot find Such Vendor Document');
            }
            $this->vendorDocumentRepository->deleteDocument($document);
            DB::commit();
        }catch(Exception $exception){
            DB::rollBack();
            throw($exception);
        }
    }


}