<?php

namespace App\Modules\Variants\Controllers\Api\Admin;
use App\Modules\Variants\Models\Variant;
use App\Modules\Variants\Requests\Variant\VariantCreateRequest;
use App\Modules\Variants\Requests\Variant\VariantUpdateRequest;
use App\Modules\Variants\Resources\MinimalVariantResource;
use App\Modules\Variants\Services\VariantService;
use App\Http\Controllers\Controller;

class VariantController extends Controller
{
    protected $variantService;

    public function __construct(VariantService $variantService)
    {
        $this->variantService = $variantService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $variantIDs = $this->variantService->getAllVariants(['variantValues']);
        return MinimalVariantResource::collection($variantIDs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VariantCreateRequest $request)
    {
        $validated = $request->validated();
        return $this->variantService->storeVariant($validated);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($variantID)
    {
        $variant = $this->variantService->findVariantById($variantID);
        return new MinimalVariantResource($variant);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VariantUpdateRequest $request,$variantID)
    {
        $validated = $request->validated();
        return $this->variantService->updateVariant($validated, $variantID);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($variantID)
    {
        return $this->variantService->deleteVariant($variantID);
    }
}
