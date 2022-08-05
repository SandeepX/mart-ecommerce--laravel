<?php

namespace App\Modules\ContentManagement\Controllers\Api\Front;

use App\Modules\ContentManagement\Resources\AboutUsResource;
use App\Modules\ContentManagement\Services\AboutUsService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class AboutUsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    private $aboutUsService;
    public function __construct(AboutUsService $aboutUsService){
    $this->aboutUsService =$aboutUsService;
    }
    public function index()
    {
        try{
            $select=['page_image','company_name','company_description','ceo_name','message_from_ceo','ceo_image'];
            $aboutUs= $this->aboutUsService->getLatestActiveAboutUs($select);
            return new AboutUsResource($aboutUs);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }


}
