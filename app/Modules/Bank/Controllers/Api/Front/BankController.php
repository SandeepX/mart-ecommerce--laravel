<?php

namespace App\Modules\Bank\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\Bank\Resources\BankResource;
use App\Modules\Bank\Services\BankService;

class BankController extends Controller
{
    protected $bankService;
   
    public function __construct(BankService $bankService)
    {
        $this->bankService = $bankService;
    }
    
    public function index()
    {
        try{
            $banks = $this->bankService->getAllBanks();
            $banks = BankResource::collection($banks);
            return sendSuccessResponse('Data Found',  $banks);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }

    }
}