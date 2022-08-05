<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/18/2020
 * Time: 11:13 AM
 */

namespace App\Modules\Career\Controllers\Api\Front;


use App\Http\Controllers\Controller;
use App\Modules\Career\Requests\JobApplicationStoreRequest;

use App\Modules\Career\Services\JobApplicationService;
use App\Modules\Career\Services\JobOpeningService;
use Exception;
use Illuminate\Validation\ValidationException;

class JobApplicationApiController extends Controller
{
    private $jobOpeningService , $jobApplicationService;

    public function __construct(JobApplicationService $jobApplicationService,
                                JobOpeningService $jobOpeningService)
    {
        $this->jobApplicationService = $jobApplicationService;
        $this->jobOpeningService = $jobOpeningService;
    }

    public function storeJobApplication(JobApplicationStoreRequest $request,$jobOpeningSlug){

        $validatedData = $request->validated();
        try{
            $jobOpening = $this->jobOpeningService->findOrFailJobOpeningBySlugWith($jobOpeningSlug,['jobQuestions']);
            $this->jobApplicationService->saveJobApplication($jobOpening,$validatedData);
            return sendSuccessResponse('You have applied for the job successfully');
        }catch(Exception $exception){
            return sendErrorResponse([$exception->getMessage()], $exception->getCode());
        }

    }
}