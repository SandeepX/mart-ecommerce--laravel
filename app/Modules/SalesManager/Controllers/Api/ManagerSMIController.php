<?php


namespace App\Modules\SalesManager\Controllers\Api;


use App\Modules\SalesManager\Requests\ManagerSMI\ManagerSMILinkRequest;
use App\Modules\SalesManager\Resources\SMIManagerResource\SMIManagerDetailResource;
use App\Modules\SalesManager\Services\ManagerSMIService;
use Exception;



class ManagerSMIController
{
    public $managerSMIService;

    public function __construct(ManagerSMIService $managerSMIService){
        $this->managerSMIService = $managerSMIService;
    }

    public function store(ManagerSMILinkRequest $linkRequest)
    {
        try {
            $managerSMIValidatedData['manager_code'] = getAuthManagerCode();
            $managerSMILinkValidatedData = $linkRequest->validated();
            $this->managerSMIService->storeManagerSMIDetail(
                $managerSMIValidatedData,$managerSMILinkValidatedData
            );
            return sendSuccessResponse('Manager SMI Data Stored Successfully');

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function show()
    {
        try{
            $select =['msmi_code','manager_code','status', 'is_active','allow_edit','remarks','allow_edit_remarks','created_at'];
            $managerSMIDetail = $this->managerSMIService->findManagerSMIDetailByManagerCode(
                getAuthManagerCode(),$select
            );
            $data = isset($managerSMIDetail) ? new SMIManagerDetailResource($managerSMIDetail):[];
            return sendSuccessResponse('Data Found',$data);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function update(ManagerSMILinkRequest $linkRequest)
    {
        try {
            $managerSMILinkValidatedData = $linkRequest->validated();
            $this->managerSMIService->updateManagerSMIDetail(
                $managerSMILinkValidatedData);
            return sendSuccessResponse('Manager SMI Data Updated Successfully');
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 400);
        }


    }


}


