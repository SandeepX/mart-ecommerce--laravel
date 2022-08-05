<?php


namespace App\Modules\SalesManager\Controllers\Api\Front\Auth;


use App\Http\Controllers\Controller;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\SalesManager\Requests\RegisterApiRequest\SalesManagerRegisterRequest;
use App\Modules\SalesManager\Resources\SalesManagerRegistrationStatusResource;
use App\Modules\SalesManager\Services\SalesManagerService;
use App\Modules\SalesManager\Services\UserSalesManagerService;
use App\Modules\User\Models\User;
use App\Modules\User\Models\UserDoc;
use App\Modules\User\Requests\UserDocRequest;
use Exception;

class SalesManagerRegisterController extends Controller
{

    private $userSalesManagerService,$salesManagerService;

    public function __construct(
        UserSalesManagerService $userSalesManagerService,
        SalesManagerService $salesManagerService
    )
    {
        $this->userSalesManagerService = $userSalesManagerService;
        $this->salesManagerService = $salesManagerService;
    }

    public function getDataRequiredForManagerRegistration()
    {
        $managerDocTypes = UserDoc::MANAGER_DOC_TYPES;
        $managerDocTypesReformed = [];
        foreach ($managerDocTypes as $managerDocType) {
            $managerDocTypesReformed[$managerDocType] = ucwords(str_replace('_', ' ', $managerDocType));
        }
        $data = [
            'doc_types' => $managerDocTypesReformed
        ];
        return sendSuccessResponse('Manager Reg. Information - Success', $data);
    }

    public function storeSalesManagerUserFromApi(
        SalesManagerRegisterRequest $salesManagerRegisterRequest,
        UserDocRequest $userDocRequest)
    {
        try {
            $validatedSalesManagerData = $salesManagerRegisterRequest->validated();

            $validatedUserDocData = $userDocRequest->validated();

            $this->userSalesManagerService->storeUserSalesManager($validatedSalesManagerData, $validatedUserDocData);
            return sendSuccessResponse('New Sales Manager Registered Successfully. Please view your mail inbox to verify your email.');
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }


    public function findSalesManagerAccountStatus()
    {
        try {
            $manager = $this->salesManagerService->findOrFailSalesManagerByCodeWith(getAuthManagerCode());
           // $salesManagerRegistrationStatus = getAuthSalesManagerRegistrationStatus();
            return sendSuccessResponse(
                'Sales Manager Account Status Fetched',
                new SalesManagerRegistrationStatusResource($manager)
            );
        } catch (\Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}
