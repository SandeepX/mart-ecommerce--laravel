<?php

namespace App\Modules\Store\Controllers\Api\Front\StoreDocument;

use App\Http\Controllers\Controller;
use App\Modules\Store\Models\StoreDocument;
use App\Modules\Store\Requests\StoreDocumentRequest;
use App\Modules\Store\Resources\StoreResource;
use App\Modules\Store\Services\StoreDocumentService;
use App\Modules\Store\Services\StoreService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;


class StoreDocumentUploadApiController extends Controller
{

    public $title = 'Store Document';

    protected $storeDocumentService, $storeService;

    public function __construct(StoreDocumentService $storeDocumentService, StoreService $storeService)
    {
        $this->storeDocumentService = $storeDocumentService;
        $this->storeService = $storeService;
    }

    public function store($storeSlug, StoreDocumentRequest $request)
    {
        $validated = $request->validated();
        try{
            $store = $this->storeService->findOrFailStoreBySlug($storeSlug);
            $this->storeDocumentService->storeStoreDocuments($validated, $store);
            return sendSuccessResponse('Documents uploaded successfully.');
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }



}
