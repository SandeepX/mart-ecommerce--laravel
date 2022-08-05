<?php

namespace App\Modules\Brand\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Brand\Models\Brand;
use App\Modules\Brand\Requests\BrandCreateRequest;
use App\Modules\Brand\Requests\BrandUpdateRequest;
use App\Modules\Brand\Resources\BrandResource;
use App\Modules\Brand\Services\BrandService;
use Exception;

class BrandController extends Controller
{

    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $brands = $this->brandService->getAllBrands();
            $brands = BrandResource::collection($brands);
            return sendSuccessResponse('Data Found', $brands);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrandCreateRequest $request)
    {
        $validated = $request->validated();
        return $this->brandService->create($validated);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        $brand = $this->brandService->getBrandById($brand->id);
        return new BrandResource($brand);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BrandUpdateRequest $request, Brand $brand)
    {
        $validated = $request->validated();
        return $this->brandService->update($validated, $brand);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        return $this->brandService->delete($brand);
    }

}
