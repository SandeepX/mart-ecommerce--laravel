<?php

namespace App\Modules\Package\Controllers\Api\Front;

use App\Http\Controllers\Controller;
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
            $packageTypes = $this->packageTypeService->getAllPackageTypes();
            $packageTypes = PackageTypeResource::collection($packageTypes);
            return sendSuccessResponse('Data Found ',  $packageTypes);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  402);
        }
    }

}