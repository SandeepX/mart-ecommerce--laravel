<?php
/**
 * Created by PhpStorm.
 * User: shramik
 * Date: 12/11/20
 * Time: 1:42 PM
 */

namespace App\Modules\Vendor\Controllers\Api\Frontend;


use App\Http\Controllers\Controller;
use App\Modules\Vendor\Requests\VendorDocumentCreateRequest;
use App\Modules\Vendor\Services\VendorDocumentService;
use App\Modules\Vendor\Services\VendorService;
use Exception;
use Illuminate\Support\Facades\DB;

class VendorDocumentController extends  Controller
{


    protected $vendorDocumentService, $vendorService;

    public function __construct(VendorDocumentService $vendorDocumentService, VendorService $vendorService)
    {
        $this->vendorDocumentService = $vendorDocumentService;
        $this->vendorService = $vendorService;
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function getAllDocuments()
    {
        try{
            $vendor = $this->vendorService->findOrFailVendorByCode(getAuthVendorCode());
            $vendorDocuments = $this->vendorDocumentService->getAllDocuments($vendor);
        }catch(Exception $exception){
            throw new Exception('Cannot Fetch Documents',$exception->getCode());
        }
        return sendSuccessResponse('Vendor Documents Fetched',$vendorDocuments);

    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function storeDocument(VendorDocumentCreateRequest $request)
    {
        $validated = $request->validated();
        try{
            $vendor = $this->vendorService->findOrFailVendorByCode(getAuthVendorCode());
            $this->vendorDocumentService->storeVendorDocuments($validated, $vendor);
        }catch(Exception $exception){
            throw new Exception('Cannot Store Document : '.$exception->getMessage(),$exception->getCode());
        }
        return sendSuccessResponse('Vendor Documents Added Succesfully');
    }



    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function deleteDocument($vendorDocumentId)
    {
        try{
            $vendor = $this->vendorService->findOrFailVendorByCode(getAuthVendorCode());
            $this->vendorDocumentService->deleteVendorDocument($vendor,$vendorDocumentId);
        }catch(Exception $exception){
            throw new Exception('Cannot Delete Document',$exception->getCode());
        }
        return sendSuccessResponse('Vendor Document Deleted Successfully');

    }

}