<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/17/2020
 * Time: 2:54 PM
 */

namespace App\Modules\Career\Controllers\Api\Front;


use App\Http\Controllers\Controller;
use App\Modules\Career\Resources\JobOpeningResource;
use App\Modules\Career\Resources\JobOpeningSingleResource;
use App\Modules\Career\Services\JobOpeningService;

use Exception;
use Illuminate\Http\Request;

class JobOpeningApiController extends Controller
{

    private $jobOpeningService;

    public function __construct(JobOpeningService $jobOpeningService){
        $this->jobOpeningService= $jobOpeningService;
    }

    public function index()
    {
        try{
            $jobOpenings = $this->jobOpeningService->getActiveJobOpenings();
            $jobOpenings = JobOpeningResource::collection($jobOpenings);
            return sendSuccessResponse('Data Found',  $jobOpenings);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }

    }

    public function showJobOpening($slug){

        try{
            $jobOpening = $this->jobOpeningService->findOrFailJobOpeningBySlugWith($slug,['jobQuestions']);
            $jobOpening = new JobOpeningSingleResource($jobOpening);
            return sendSuccessResponse('Data Found',  $jobOpening);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

}