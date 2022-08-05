<?php

namespace App\Modules\ContentManagement\Controllers\Api\Front;

//use App\Modules\ContentManagement\Resources\AboutUsResource;
use App\Modules\ContentManagement\Resources\CompanyTimelineResource;
use App\Modules\ContentManagement\Services\CompanyTimelineService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class CompanyTimelineController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    private $companyTimelineService;
    public function __construct(CompanyTimelineService $companyTimelineService){
        $this->companyTimelineService =$companyTimelineService;
    }
    public function index()
    {
        try{
            $select = ['year','title','description'];
            $companyTimeline= $this->companyTimelineService->getLatestActiveCompanyTimeline($select);
            return sendSuccessResponse('Data Found',  $companyTimeline);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }


}
