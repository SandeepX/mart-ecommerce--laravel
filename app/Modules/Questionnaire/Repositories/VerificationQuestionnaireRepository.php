<?php

namespace App\Modules\Questionnaire\Repositories;

use App\Modules\Questionnaire\Models\ActionVerificationQuestionnaire;
use App\Modules\User\Models\UserDoc;
use Exception;

class VerificationQuestionnaireRepository
{

    public function findOrFailByActionVerificationQuestionCode($avqCode){
        $actionVerificationQuestion =  ActionVerificationQuestionnaire::where('avq_code',$avqCode)->first();
        if(!$actionVerificationQuestion){
          throw new Exception('Action Verification Not Found The Given Code');
        }
        return $actionVerificationQuestion;
    }
    public function getAllVerificationQuestions()
    {
        return ActionVerificationQuestionnaire::orderBy('avq_code','DESC')->paginate(10);
    }

    public function storeActionQuestions($validatedData){
         $actionVerificationQuestion = ActionVerificationQuestionnaire::create($validatedData);
         return $actionVerificationQuestion->fresh();
    }

    public function updateActionQuestion(ActionVerificationQuestionnaire $actionVerificationQuestionnaire,$validatedData){
       // dd($validatedData);
        $actionVerificationQuestionnaire->update($validatedData);
        return $actionVerificationQuestionnaire->fresh();
    }

    public function delete(ActionVerificationQuestionnaire $actionVerificationQuestionnaire){
        $actionVerificationQuestionnaire->delete();
        $actionVerificationQuestionnaire->deleted_by = getAuthUserCode();
        $actionVerificationQuestionnaire->save();
        return $actionVerificationQuestionnaire;
    }

    public function getVerificationQuestionsByEntityAndAction($entity,$action,$select = '*'){
        $verificationQuestions = ActionVerificationQuestionnaire::select($select)->where('entity',$entity)
                                               ->where('action',$action)
                                               ->where('is_active',1)
                                               ->get();

        return $verificationQuestions;
    }

}
