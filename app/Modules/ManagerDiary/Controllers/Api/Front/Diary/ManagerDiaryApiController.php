<?php

namespace App\Modules\ManagerDiary\Controllers\Api\Front\Diary;

use App\Http\Controllers\Controller;
use App\Modules\ManagerDiary\Helpers\ManagerDiaryFilter;
use App\Modules\ManagerDiary\Models\Diary\ManagerDiary;
use App\Modules\ManagerDiary\Requests\Diary\StoreManagerDiaryRequest;
use App\Modules\ManagerDiary\Requests\Diary\UpdateManagerDiaryRequest;
use App\Modules\ManagerDiary\Resources\Diary\ManagerDiaryCollection;
use App\Modules\ManagerDiary\Resources\Diary\ManagerDiaryResource;
use App\Modules\ManagerDiary\Services\Diary\ManagerDiaryService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function getAuthManagerCode;
use function sendErrorResponse;
use function sendSuccessResponse;

class ManagerDiaryApiController extends Controller
{

    public $managerDiaryService;
    public function __construct(ManagerDiaryService $managerDiaryService)
    {
        $this->managerDiaryService = $managerDiaryService;
    }

    public function index(Request $request){
        try{
            $filterParameters = [
                'manager_code' => getAuthManagerCode(),
                'store_name' => $request->get('store_name'),
                'is_referred' => $request->get('is_referred'),
                'owner_name' => $request->get('owner_name'),
                'phone_no' => $request->get('phone_no'),
                'amount_condition' => $request->get('amount_condition'),
                'amount' => $request->get('amount'),
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to'),
                'province_code'=> $request->get('province_code'),
                'district_code'=> $request->get('district_code'),
                'municipality_code'=> $request->get('municipality_code'),
                'ward_code' => $request->get('ward_code'),
                'records_per_page' => $request->get('records_per_page'),
                'amount_from' => $request->get('amount_from'),
                'amount_to' => $request->get('amount_to')
            ];

            $paginateBy = ManagerDiary::PAGINATE_BY;
            $managerDiaries = ManagerDiaryFilter::filterPaginatedManagerDiary($filterParameters,$paginateBy);
            return new ManagerDiaryCollection($managerDiaries);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function show($managerDiaryCode){
        try{
            $managerDiary = $this->managerDiaryService->findOrFailManagerDiaryByCode($managerDiaryCode);
            $managerDiary = new ManagerDiaryResource($managerDiary);
            return sendSuccessResponse('Data Found',$managerDiary);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function store(StoreManagerDiaryRequest $request){
       try{
           $validatedData = $request->validated();
           DB::beginTransaction();
           $managerDiary =  $this->managerDiaryService->saveManagerDiaryDetails($validatedData);
           $managerDiary = new ManagerDiaryResource($managerDiary);
           DB::commit();
           return sendSuccessResponse('Manager diary created successfully',$managerDiary);
       }catch (Exception $exception){
           DB::rollBack();
           return sendErrorResponse($exception->getMessage(),$exception->getCode());
       }
    }

    public function update(UpdateManagerDiaryRequest $request,$managerDiaryCode){
        try{
            $validatedData = $request->validated();
            DB::beginTransaction();
            $managerDiary =  $this->managerDiaryService->updateManagerDiaryDetails($managerDiaryCode,$validatedData);
            $managerDiary = new ManagerDiaryResource($managerDiary);
            DB::commit();
            return sendSuccessResponse('Manager diary detail updated successfully',$managerDiary);
        }catch (Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

}
