<?php

namespace App\Modules\ManagerDiary\Controllers\Web\Admin\Diary;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Location\Services\LocationHierarchyService;
use App\Modules\ManagerDiary\Helpers\ManagerDiaryFilter;
use App\Modules\ManagerDiary\Models\Diary\ManagerDiary;
use App\Modules\ManagerDiary\Services\Diary\ManagerDiaryService;
use App\Modules\SalesManager\Services\SalesManagerService;
use Exception;
use Illuminate\Http\Request;
use function redirect;
use function view;

class ManagerDiaryAdminController extends BaseController
{
    public $title = 'Manager Diaries';
    public $base_route = 'admin.manager-diaries';
    public $sub_icon = 'home';
    public $module = 'ManagerDiary::';
    private $view = 'admin.manager-diary.';

    private $salesManagerService;
    private $locationHierarchyService;
    private $managerDiaryService;
    public function __construct(
        SalesManagerService  $salesManagerService,
        LocationHierarchyService $locationHierarchyService,
        ManagerDiaryService $managerDiaryService
    ){
        $this->salesManagerService = $salesManagerService;
        $this->locationHierarchyService = $locationHierarchyService;
        $this->managerDiaryService = $managerDiaryService;
    }

    public function index(Request $request,$managerCode){
        try{
            $filterParameters = [
                'manager_code' => $managerCode,
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
                'records_per_page' => $request->get('records_per_page')
            ];

            //dd($filterParameters);

            $priceConditions=[
                'Greater Than >'=>'>',
                'Less Than <'=>'<' ,
                'Greater Than & Equal To >='=>'>=' ,
                'Less Than & Equal To <='=>'<=',
                'Equal To ='=>'=',
            ];

            $paginateBy = ManagerDiary::PAGINATE_BY;
            $manager = $this->salesManagerService->findOrFailSalesManagerByCodeWith($managerCode);
            $managerDiaries = ManagerDiaryFilter::filterPaginatedManagerDiary($filterParameters,$paginateBy);
            $provinces = $this->locationHierarchyService->getAllLocationsByType('province');

            return view(parent::loadViewData($this->module.$this->view.'index'),compact('priceConditions','managerDiaries','manager','filterParameters','provinces'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function showManagerDiaryDetail($managerDiaryCode){
        try{
            $with = ['referredStore','createdBy'];
            $managerDiary = $this->managerDiaryService->findOrFailManagerDiaryByCode($managerDiaryCode,$with);
            return view(parent::loadViewData($this->module.$this->view.'show'),compact('managerDiary'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }


}
