<?php

namespace App\Modules\Questionnaire\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Questionnaire\Services\VerificationQuestionnaireService;
use Exception;

class ActionVerificationQuestionsApiController extends Controller
{
    private $verificationQuestionnaireService;

    public function __construct(
        VerificationQuestionnaireService $verificationQuestionnaireService
    ){
     $this->verificationQuestionnaireService = $verificationQuestionnaireService;
    }

    public function getVerificationQuestionByEntityAndAction($entity,$action){
        try{
            $select = ['avq_code','question'];
            $verificationQuestions = $this->verificationQuestionnaireService->getVerificationQuestionsByEntityAndAction($entity,$action,$select);
            return sendSuccessResponse('Data Found',$verificationQuestions);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}


