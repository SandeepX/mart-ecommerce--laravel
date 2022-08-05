<?php

namespace App\Modules\Brand\Controllers\Api\Front;

use App\Modules\Brand\Resources\BrandSliderResource;
use App\Modules\Brand\Services\BrandSliderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class BrandSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    private $brandSliderService;
    public function __construct(BrandSliderService $brandSliderService){
        $this->brandSliderService =$brandSliderService;
    }
    public function index($brandSlug)
    {
        try{
            $select=['image','description'];
            $brandSlider= $this->brandSliderService->getAllActiveBrandSliderByBrandSlug($brandSlug,$select);
            $slider= BrandSliderResource::collection($brandSlider);
            return sendSuccessResponse('Data Found',$slider);

        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }


}
