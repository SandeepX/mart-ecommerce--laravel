<?php

namespace App\Modules\Package\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Package\Models\PackageType;
use App\Modules\Package\Requests\PackageTypeCreateRequest;
use App\Modules\Package\Requests\PackageTypeUpdateRequest;
use App\Modules\Package\Resources\PackageTypeResource;
use App\Modules\Package\Services\PackageTypeService;
use Exception;

class PackageTypeController extends Controller
{
    protected $packageTypeService;

    public function __construct(PackageTypeService $packageTypeService)
    {
        $this->packageTypeService = $packageTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $packageType = $this->packageTypeService->getAllPackageTypes();
            return PackageTypeResource::collection($packageType);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  402);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PackageTypeCreateRequest $request)
    {
        try {
            $validated = $request->validated();
            $packageType =  $this->packageTypeService->create($validated);
            $packageType = new PackageTypeResource($packageType);
            return sendSuccessResponse('Package Type Created Successfully', $packageType);
            
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  402);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($packageTypeCode)
    {
        try {
            $packageType = $this->packageTypeService->getPackageTypeByCode($packageTypeCode);
            return new PackageTypeResource($packageType);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  402);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PackageTypeUpdateRequest $request, $packageTypeCode)
    {
        try {
            $validated = $request->validated();
            $packageType = $this->packageTypeService->updatePackageType($validated, $packageTypeCode);
            $packageType = new PackageTypeResource($packageType);
            return sendSuccessResponse('Package Type Updated Successfully', $packageType);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  402);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($packageTypeCode)
    {
        try {
            $packageType =  $this->packageTypeService->delete($packageTypeCode);
            $packageType = new PackageTypeResource($packageType);
            return sendSuccessResponse('Package Type Deleted Successfully', $packageType);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  402);
        }
    }
}
