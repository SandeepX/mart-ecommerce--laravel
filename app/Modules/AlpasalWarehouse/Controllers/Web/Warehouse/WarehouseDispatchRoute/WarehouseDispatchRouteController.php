<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\WarehouseDispatchRoute;


use App\Http\Controllers\Controller;

use App\Modules\AlpasalWarehouse\Requests\WarehouseDispatchRoute\WarehouseDispatchRouteCreateRequest;
use App\Modules\AlpasalWarehouse\Requests\WarehouseDispatchRoute\WarehouseDispatchRouteFinalUpdateRequest;
use App\Modules\AlpasalWarehouse\Requests\WarehouseDispatchRoute\WarehouseDispatchRouteMinimalUpdateRequest;
use App\Modules\AlpasalWarehouse\Resources\WarehouseDispatchRoute\DispatchableStoreResource;
use App\Modules\AlpasalWarehouse\Resources\WarehouseDispatchRoute\WarehouseDispatchRouteDetailResource;
use App\Modules\AlpasalWarehouse\Resources\WarehouseDispatchRoute\WarehouseDispatchRouteResource;
use App\Modules\AlpasalWarehouse\Services\WarehouseDispatchRoute\WarehouseDispatchRouteService;
use App\Modules\Questionnaire\Exceptions\QuestionnaireVerificationException;
use App\Modules\Questionnaire\Helpers\ActionVerificationQuestionsHelper;
use Exception;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WarehouseDispatchRouteController extends Controller
{
    private $warehouseDispatchRouteService;

    public function __construct(WarehouseDispatchRouteService $warehouseDispatchRouteService)
    {
        $this->warehouseDispatchRouteService = $warehouseDispatchRouteService;
    }

    public function getAvailableStores()
    {
        try {
            $stores = $this->warehouseDispatchRouteService->getAvailableStoresForDispatch();
            return DispatchableStoreResource::collection($stores);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getWarehouseDispatchRoutes(Request $request)
    {
        try {
            $filterParameters = [
                'status' => $request->status
            ];
            $dispatchRoutes = $this->warehouseDispatchRouteService->filterWarehouseDispatchRoutes($filterParameters);
            return WarehouseDispatchRouteResource::collection($dispatchRoutes);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function showWarehouseDispatchRouteDetail($dispatchRouteCode)
    {
        try {
            $dispatchRouteDetail = $this->warehouseDispatchRouteService->getWarehouseDispatchRouteDetail($dispatchRouteCode);
            // return  $dispatchRouteDetail;
            return new WarehouseDispatchRouteDetailResource($dispatchRouteDetail);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function saveWarehouseDispatchRouteWithStores(WarehouseDispatchRouteCreateRequest $request)
    {
        try {
            $validatedData = $request->validated();
            //dd($validatedData);
            $dispatchRoute = $this->warehouseDispatchRouteService->createDispatchRouteWithStores($validatedData);

            return sendSuccessResponse('New dispatch route created successfully', $dispatchRoute);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function updateMinimalWarehouseDispatchRoute(WarehouseDispatchRouteMinimalUpdateRequest $request, $dispatchRouteCode)
    {
        try {
            $validatedData = $request->validated();
            //dd($validatedData);
            $this->warehouseDispatchRouteService->updateDispatchRouteName($dispatchRouteCode, $validatedData);
            return sendSuccessResponse('Route name updated successfully');
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function finalDispatchWarehouseRoute(WarehouseDispatchRouteFinalUpdateRequest $request, $dispatchRouteCode)
    {
        try {
            $validatedData = $request->validated();

            $action = 'dispatch_route_verification';
            $entity = 'orders';
            $validatedData['question_checked_meta'] =  ActionVerificationQuestionsHelper::validateActionVerificationQuestions($request,$entity,$action);
            //dd($validatedData);
            $this->warehouseDispatchRouteService->dispatchWarehouseRouteFinal($dispatchRouteCode, $validatedData);
            return sendSuccessResponse('Route dispatched successfully');
        } catch (Exception $exception) {
            if($exception instanceof QuestionnaireVerificationException){
                return sendErrorResponse($exception->getData()['validator'], $exception->getCode());
            }
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function deleteDispatchRoute($dispatchRouteCode){
        try {
            $this->warehouseDispatchRouteService->deleteWarehouseDispatchRoute($dispatchRouteCode);
            return sendSuccessResponse('Route deleted successfully');
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }


}
