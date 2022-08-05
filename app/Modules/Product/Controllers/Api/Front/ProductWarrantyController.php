<?php

namespace App\Modules\Product\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\Product\Resources\ProductWarranty\ProductWarrantyResource;
use App\Modules\Product\Services\ProductWarranty\ProductWarrantyService;
use Exception;

class ProductWarrantyController extends Controller
{
    protected $productWarrantyService;

    public function __construct(ProductWarrantyService $productWarrantyService)
    {
        $this->productWarrantyService = $productWarrantyService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $productWarranties = $this->productWarrantyService->getAllProductWarranties();
            $productWarranties = ProductWarrantyResource::collection($productWarranties);
            return sendSuccessResponse('Data Found', $productWarranties);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  400);
        }
    }

}