<?php

namespace App\Modules\Career\Controllers\Api\Front;

use App\Modules\Career\Requests\CandidateCreateRequest;
use App\Modules\Career\Services\CandidateService;
use App\Http\Controllers\Controller;

class CandidateApiController extends Controller
{
    private $candidateService;

    public function __construct(CandidateService $candidateService){
        $this->candidateService=$candidateService;
    }
    public function createCandidate(CandidateCreateRequest $candidateCreateRequest){
        $validatedData =$candidateCreateRequest->validated();
        try{
            $this->candidateService->createCandidate($validatedData);
            return sendSuccessResponse('You have applied for the job successfully');
        }
        catch(Exception $exception){
            return sendErrorResponse([$exception->getMessage()], $exception->getCode());
        }

    }
}
