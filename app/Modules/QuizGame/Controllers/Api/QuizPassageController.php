<?php


namespace App\Modules\QuizGame\Controllers\Api;

use App\Modules\QuizGame\Resources\QuizPassageDetailResource;
use App\Modules\QuizGame\Services\QuizService;
use App\Modules\QuizGame\Transformers\QuizGameDetailTransformer;

class QuizPassageController
{
    private $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function getPassageDetailOfTheDayAlongWithQuestion()
    {
        try {
            $quizPassage = (new QuizGameDetailTransformer())->transform();
            return sendSuccessResponse('Data Found',$quizPassage);
        } catch (\Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

}
