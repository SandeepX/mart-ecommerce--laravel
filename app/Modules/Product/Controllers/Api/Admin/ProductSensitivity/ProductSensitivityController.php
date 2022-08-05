<?php

namespace App\Modules\Product\Controllers\Api\Admin\ProductSensitivity;

use App\Http\Controllers\Controller;
use App\Modules\Product\Requests\ProductSensitivity\ProductSensitivityCreateRequest;
use App\Modules\Product\Requests\ProductSensitivity\ProductSensitivityUpdateRequest;
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
            $productSensitivity = $this->productSensitivityService->getAllProductSensitivities();
            return ProductSensitivityResource::collection($productSensitivity);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 402);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductSensitivityCreateRequest $request)
    {
        try {

            $validated = $request->validated();
            $productSensitivity = $this->productSensitivityService->create($validated);
            return sendSuccessResponse('Product Sensitivity Created Successfully',  $productSensitivity);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  $exception->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($productSensitivityCode)
    {
        try {
            $productSensitivity = $this->productSensitivityService->getproductSensitivityByCode($productSensitivityCode);
            return new ProductSensitivityResource($productSensitivity);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  $exception->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductSensitivityUpdateRequest $request, $productSensitivityCode)
    {
        try {
            $validated = $request->validated();
            $productSensitivity = $this->productSensitivityService->update($validated, $productSensitivityCode);
            return sendSuccessResponse('Product Sensitivity Updated Successfully',  $productSensitivity);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  $exception->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($productSensitivityCode)
    {
        try {
            $productSensitivity = $this->productSensitivityService->delete($productSensitivityCode);
            return sendSuccessResponse('Product Sensitivity Deleted Successfully',  $productSensitivity);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  $exception->getCode());
        }
    }
}
