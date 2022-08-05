<?php


namespace App\Modules\QuizGame\Services;

use App\Modules\QuizGame\Repositories\QuizSubmissionDetailRepository;
use Exception;


class QuizSubmittedDetailService
{
    private $quizSubmissionDetailRepo;

    public function __construct(QuizSubmissionDetailRepository $quizSubmissionDetailRepo)
    {
        $this->quizSubmissionDetailRepo = $quizSubmissionDetailRepo;
    }

    public function getQuizSubmittedDetailByQSCode($qs_code, $select = [], $with = [])
    {
        try {
            $quizSubmittedDetail = $this->quizSubmissionDetailRepo
                ->with($with)
                ->select($select)
                ->getQuizSubmittedDetailByQSCode($qs_code);
            if(!$quizSubmittedDetail){
                throw new Exception('Quiz Submitted Detail Not Found');
            }
            return $quizSubmittedDetail;
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}

