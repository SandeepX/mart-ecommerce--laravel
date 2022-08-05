<?php


namespace App\Modules\QuizGame\Transformers;

use App\Modules\Location\Repositories\LocationHierarchyRepository;
use App\Modules\QuizGame\Repositories\QuizParticipatorRepository;
use App\Modules\QuizGame\Repositories\QuizPassageRepository;
use App\Modules\QuizGame\Repositories\QuizSubmissionRepository;
use App\Modules\QuizGame\Resources\QuizOnlyPassageDetailResource;
use App\Modules\QuizGame\Resources\QuizQuestionCollection;
use App\Modules\QuizGame\Resources\QuizSubmittedDetail\QuizSubmittedDetailCollection;

class QuizGameDetailTransformer
{
    public function transform()
    {

        $canSubmit = true;
        $quizPassageDetail = [];
        $quizSubmissionOfTheDay = [];

        $participatorCode = $this->getParticipatorCode();

        $quizCompitatorDetail = (new QuizParticipatorRepository)
            ->findQuizParticipatorDetailByParticipatorCode($participatorCode);

        $storeLocation = (new LocationHierarchyRepository)->getLocationByCode($quizCompitatorDetail->store_location_ward_code);
        $storeLocTree = (new LocationHierarchyRepository)->getLocationPath($storeLocation);

        if(($quizCompitatorDetail) && $quizCompitatorDetail['status'] == 'approved'){
            $quizPassageDetail = (new QuizPassageRepository)->findPassageDetailOfTheDay();
            if($quizPassageDetail){
                $quizSubmissionOfTheDay = (new QuizSubmissionRepository)
                    ->getQuizSubmissionDetailByQPCode($quizPassageDetail['qp_code']);
                if(!empty($quizSubmissionOfTheDay) && count($quizSubmissionOfTheDay)>0){
                    $quizPassageDetail = [];
                    $canSubmit = false;
                }
            }
        }else{
            $canSubmit = false;
        }

        return [
            'can_submit' => $canSubmit,
            'quiz_participator_detail' => ($quizCompitatorDetail) ? [
                'qpd_code' => $quizCompitatorDetail['qpd_code'],
                'participator_type' => $quizCompitatorDetail['participator_type'],
                'participator_code' => $quizCompitatorDetail['participator_code'],
                'store_name' => $quizCompitatorDetail['store_name'],
                'store_pan_no' => $quizCompitatorDetail['store_pan_no'],
                'store_location_ward_code' => $quizCompitatorDetail['store_location_ward_code'],
                'location_details' => [
                    'province' => $storeLocTree['province']['location_name'],
                    'district' => $storeLocTree['district']['location_name'],
                    'municipality' => $storeLocTree['municipality']['location_name'],
                    "ward" => $storeLocTree['ward']['location_name'],
                ],
                'store_full_location' => $quizCompitatorDetail['store_full_location'],
                'recharge_phone_no' => $quizCompitatorDetail['recharge_phone_no'],
                'status' => $quizCompitatorDetail['status']
            ] : [],
            'quiz_passage_detail' => ($quizPassageDetail)? ([
                'qp_code' => $quizPassageDetail['qp_code'],
                'passage_title' => $quizPassageDetail['passage_title'],
                'passage' => $quizPassageDetail['passage'],
                'passage_questions_details' => new QuizQuestionCollection($quizPassageDetail->isActiveQuizQuestion)
            ]) : null,
            'quiz_submission_detail' => (!empty($quizSubmissionOfTheDay)  && count($quizSubmissionOfTheDay)>0)? ([
                'passage_detail' => new QuizOnlyPassageDetailResource(($quizSubmissionOfTheDay)[0]->quizPassage),
                'submitted_quiz_detail'=> new QuizSubmittedDetailCollection($quizSubmissionOfTheDay[0]->quizSubmissionDetail)
             ]): null
        ];

    }

    public function getParticipatorCode()
    {
        $userType = getAuthParentUserType();
        if($userType == 'manager'){
            $participatorCode = getAuthManagerCode();
        }
        if ($userType == 'store'){
            $participatorCode = getAuthStoreCode();
        }
        if($userType == 'normal-user'){
            $participatorCode = getAuthUserCode();
        }
        return $participatorCode;
    }
}

