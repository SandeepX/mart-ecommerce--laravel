<?php

namespace App\Modules\Variants\Controllers\Api\Front;
use App\Modules\Variants\Resources\MinimalVariantResource;
use App\Modules\Variants\Services\VariantService;
use App\Http\Controllers\Controller;
use App\Modules\Variants\Resources\VariantWithValuesResource;
use Exception;

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
    {   try{
            $variants = $this->variantService->getAllVariants();
            $variants = VariantWithValuesResource::collection($variants);
            return sendSuccessResponse('Data Found',  $variants);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
        
    }
}