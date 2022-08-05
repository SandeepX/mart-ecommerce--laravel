<?php

namespace App\Modules\Home\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\Home\Resources\Slider\SliderResource;
use App\Modules\Home\Services\Slider\SliderService;
use Exception;

class HomeController extends Controller
{
    protected $sliderService;

    public function __construct(SliderService $sliderService)
    {
        $this->sliderService = $sliderService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllActiveSliders()
    {
        try {
            $sliders= $this->sliderService->getActiveSliders();
            $sliders = SliderResource::collection($sliders);
            return sendSuccessResponse('Data Found', $sliders);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  400);
        }
    }

}