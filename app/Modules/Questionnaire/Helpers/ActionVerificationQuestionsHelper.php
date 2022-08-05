<?php


namespace App\Modules\Questionnaire\Helpers;

use App\Modules\Questionnaire\Exceptions\QuestionnaireVerificationException;
use App\Modules\Questionnaire\Repositories\VerificationQuestionnaireRepository;
use App\Modules\Questionnaire\Services\VerificationQuestionnaireService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exception;
use Illuminate\Support\Facades\Session;


class ActionVerificationQuestionsHelper
{


    public static function validateActionVerificationQuestions($request,$entity,$action)
    {
        try{
            $combinedQuestionAnswers = null;
            $verificationQuestionnaireRepo = new VerificationQuestionnaireRepository();
            $verificationQuestions = (new VerificationQuestionnaireService($verificationQuestionnaireRepo))
                ->getVerificationQuestionsByEntityAndAction($entity,$action);


            if(count($verificationQuestions) > 0){
                $questionnareValidator =Validator::make($request->all(),[
                    'avq_code' => ['required','array'],
                    'avq_code.*' => ['nullable'],
                    'answers' => ['required','array'],
                    'answers.*' => ['nullable',Rule::in(1)],
                ]);
                if($questionnareValidator->fails()){
                    throw new QuestionnaireVerificationException('Questionnaire Verification Failed ',[
                        'validator' => $questionnareValidator
                    ]);
                }
                $validatedData = $questionnareValidator->validated();
                $questionCodeToCheck = $verificationQuestions->pluck('avq_code')->toArray();
                $questionNames = $verificationQuestions->pluck('question')->toArray();

                // dd($questionCodeToCheck,$questionNames);
                $countArrayDiff1 =count(array_diff($questionCodeToCheck,$validatedData['avq_code']));
                $countArrayDiff2 =count(array_diff($validatedData['avq_code'],$questionCodeToCheck));
                if($countArrayDiff1 !=0 || $countArrayDiff2 !=0){
                    throw new Exception('Invalid questions');
                }

                $questionCount = count($questionCodeToCheck);
                $answersCount = count($validatedData['answers']);

                if($questionCount != $answersCount){
                    throw new Exception('Invalid answers');
                }
                $combinedQuestionAnswers = array_combine($questionNames,$validatedData['answers']);
                return json_encode($combinedQuestionAnswers);
            }
            return $combinedQuestionAnswers;
        }catch (Exception $exception){
            throw $exception;
        }

    }





}
