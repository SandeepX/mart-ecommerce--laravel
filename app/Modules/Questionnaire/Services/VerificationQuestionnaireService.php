<?php

namespace App\Modules\Questionnaire\Services;

use App\Modules\Questionnaire\Models\ActionVerificationQuestionnaire;
use App\Modules\Questionnaire\Repositories\VerificationQuestionnaireRepository;
use Exception;

class VerificationQuestionnaireService
{
    private $verificationQuestionnaireRepository;

    public function __construct(
        VerificationQuestionnaireRepository $verificationQuestionnaireRepository
    ){
        $this->verificationQuestionnaireRepository = $verificationQuestionnaireRepository;
    }

    public function findOrFailByActionVerificationQuestionCode($avqCode){
        return $this->verificationQuestionnaireRepository->findOrFailByActionVerificationQuestionCode($avqCode);
    }

    public function getAllVerificationQuestions()
    {
        return $this->verificationQuestionnaireRepository->getAllVerificationQuestions();
    }

    public function storeActionVerificationQuestions($validatedData)
    {
        try{
            $validatedData['created_by'] = getAuthUserCode();
            $validatedData['updated_by'] = getAuthUserCode();
            $actionVerificationQuestion = $this->verificationQuestionnaireRepository->storeActionQuestions($validatedData);
             return $actionVerificationQuestion;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function updateActionVerificationQuestion($avqCode,$validatedData){
        try{
            $validatedData['updated_by'] = getAuthUserCode();
            $actionVerificationQuestion = $this->verificationQuestionnaireRepository->findOrFailByActionVerificationQuestionCode($avqCode);
            $actionVerificationQuestion= $this->verificationQuestionnaireRepository->updateActionQuestion($actionVerificationQuestion,$validatedData);
            return $actionVerificationQuestion;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function deleteActionVerificationQuestions($avqCode){
        $actionVerificationQuestion = $this->verificationQuestionnaireRepository->findOrFailByActionVerificationQuestionCode($avqCode);
        return $this->verificationQuestionnaireRepository->delete($actionVerificationQuestion);
    }

    public function getVerificationQuestionsByEntityAndAction($entity,$action,$select = '*'){
        try{
            $entities = ActionVerificationQuestionnaire::entity;
            $actions = ActionVerificationQuestionnaire::action;
            if(!in_array($entity,$entities)){
               throw new Exception("The given entity doesn't exist!");
            }
            if(!in_array($action,$actions)){
                throw new Exception("The given action doesn't exist!");
            }
           $verificationQuestions = $this->verificationQuestionnaireRepository->getVerificationQuestionsByEntityAndAction($entity,$action,$select);
           return $verificationQuestions;
        }catch (Exception $exception){
            throw $exception;
        }
    }

}
