<?php

namespace App\Modules\Product\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\Product\Resources\ProductSensitivity\ProductSensitivityResource;
use App\Modules\Product\Services\ProductSensitivity\ProductSensitivityService;
use Exception;

class ProductSensitivityController extends Controller
{
    protected $productSensitivityService;

    public function __construct(ProductSensitivityService $productSensitivityService)
    {
        $this->productSensitivityService = $productSensitivityService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $productSensitivities = $this->productSensitivityService->getAllProductSensitivities();
            $productSensitivities = ProductSensitivityResource::collection($productSensitivities);
            return sendSuccessResponse('Data Found', $productSensitivities);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  400);
        }
    }

}