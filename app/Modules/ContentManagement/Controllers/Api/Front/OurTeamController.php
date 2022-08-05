<?php

namespace App\Modules\ContentManagement\Controllers\Api\Front;


use App\Modules\ContentManagement\Resources\OurTeamCollection;
use App\Modules\ContentManagement\Resources\OurTeamResource;
use App\Modules\ContentManagement\Services\OurTeamService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class OurTeamController extends Controller
{
    private $ourTeamService;
    public function __construct(OurTeamService $ourTeamService){
        $this->ourTeamService =$ourTeamService;
    }
    public function index()
    {
        try{
            $select=['image','name','department','delegation','message'];
            $ourTeam= $this->ourTeamService->getLatestActiveOurTeam($select);
            return new OurTeamCollection($ourTeam);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
    public function testimonial(){
        try{
            $select=['image','name','message','department','delegation'];
            $ourTeam= $this->ourTeamService->getActiveTestimonial($select);
            return new OurTeamCollection($ourTeam);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }


}
