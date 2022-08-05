<?php

namespace App\Modules\Product\Controllers\Api\Admin\ProductWarranty;

use App\Http\Controllers\Controller;
use App\Modules\Package\Requests\ProductWarranty\ProductWarrantyCreateRequest;
use App\Modules\Package\Requests\ProductWarranty\ProductWarrantyUpdateRequest;
use App\Modules\Package\Resources\ProductWarranty\ProductWarrantyResource;
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductWarrantyCreateRequest $request)
    {
        try {
            $validated = $request->validated();
            $productWarranty = $this->productWarrantyService->create($validated);
            return $productWarranty;
            $productWarranty = new ProductWarrantyResource($productWarranty);
            return sendSuccessResponse('Product Warranty Created Successfully',  $productWarranty);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($productWarrantyCode)
    {
        try {
            $productWarranty = $this->productWarrantyService->getproductWarrantyByCode($productWarrantyCode);
            return new ProductWarrantyResource($productWarranty);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductWarrantyUpdateRequest $request, $productWarrantyCode)
    {
        try {
            $validated = $request->validated();
            $productWarranty = $this->productWarrantyService->update($validated, $productWarrantyCode);
            return sendSuccessResponse('Product Warranty Updated Successfully',  $productWarranty);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($productWarrantyCode)
    {
        try {
            $productWarranty = $this->productWarrantyService->delete($productWarrantyCode);
            return sendSuccessResponse('Product Warranty Deleted Successfully',  $productWarranty);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  400);
        }
    }
}
