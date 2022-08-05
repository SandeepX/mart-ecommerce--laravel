<?php

namespace App\Modules\Types\Controllers\Api\Frontend;

use App\Modules\Types\Services\CancellationParamService;
use Exception;

class CancellationParamController
{
    private $cancellationParamService;
    public function __construct(CancellationParamService $cancellationParamService)
    {
        $this->cancellationParamService = $cancellationParamService;
    }

    public function index()
    {
        try{
            $cancellationParams = $this->cancellationParamService->getAllCancellationParams();
            return sendSuccessResponse('Data Found!', $cancellationParams);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        } 
    }
}