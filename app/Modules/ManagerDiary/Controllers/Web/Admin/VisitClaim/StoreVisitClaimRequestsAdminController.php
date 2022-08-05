<?php

namespace App\Modules\ManagerDiary\Controllers\Web\Admin\VisitClaim;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\ManagerDiary\Helpers\StoreVisitClaimRequestHelper;
use App\Modules\ManagerDiary\Helpers\StoreVisitClaimRequestsFilter;
use App\Modules\ManagerDiary\Models\VisitClaim\StoreVisitClaimRequestByManager;
use App\Modules\ManagerDiary\Requests\VisitClaim\RespondToVisitClaimRequest;
use App\Modules\ManagerDiary\Services\VisitClaim\StoreVisitClaimRequestByManagerService;
use Illuminate\Http\Request;
use Exception;

class StoreVisitClaimRequestsAdminController extends BaseController
{
    public $title = 'Store Visit Claim requests By manager';
    public $base_route = 'admin.store-visit-claim-requests';
    public $sub_icon = 'home';
    public $module = 'ManagerDiary::';
    private $view = 'admin.visit-claim.';

    public $storeVisitClaimRequestByManagerService;
    public function __construct(StoreVisitClaimRequestByManagerService $storeVisitClaimRequestByManagerService)
    {
        $this->storeVisitClaimRequestByManagerService = $storeVisitClaimRequestByManagerService;
    }

    public function getAllStoreVisitClaimRequests(Request $request){
        try{
            $filterParameters = [
                'store_name' => $request->get('store_name'),
                'status' => $request->get('status'),
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to'),
                'records_per_page' => $request->get('records_per_page')
            ];
            $paginateBy = StoreVisitClaimRequestByManager::PAGINATE_BY;
            $with = ['managerDiary'];
            $storeVisitClaimRequests =StoreVisitClaimRequestsFilter::filterStoreVisitClaimRequestOfManager($filterParameters,$paginateBy,$with);
            return view(parent::loadViewData($this->module.$this->view.'index'),compact('storeVisitClaimRequests','filterParameters'));
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger',$exception->getMessage());
        }
    }

    public function showStoreVisitClaimRequestDetails($storeVisitClaimCode){
        try{
            $with = ['managerDiary'];
            $storeVisitClaimRequest = $this->storeVisitClaimRequestByManagerService->findorFailStoreVisitClaimRequestByCode($storeVisitClaimCode,$with);
            $mapLocations =  StoreVisitClaimRequestHelper::getMapsLocationOfVisitClaim($storeVisitClaimRequest);
            return view(parent::loadViewData($this->module.$this->view.'show'),compact('storeVisitClaimRequest','mapLocations'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function getStoreVisitClaimRequestRespondForm($storeVisitClaimCode){
        try{
            $with = ['managerDiary'];
            $storeVisitClaimRequest = $this->storeVisitClaimRequestByManagerService
                                           ->findorFailStoreVisitClaimRequestByCode($storeVisitClaimCode,$with);

            if($storeVisitClaimRequest->responded_at){
               throw new Exception('This store visit claim request is already responded :(');
            }
            if($storeVisitClaimRequest->status != 'pending'){
                throw new Exception('This store visit claim request should be in pending state :(');
            }
            return view(Parent::loadViewData($this->module.$this->view.'respond.create'),compact('storeVisitClaimRequest'));
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function respondToStoreVisitClaimRequest(RespondToVisitClaimRequest $request,$storeVisitClaimCode){
        try{
            $validatedData = $request->validated();
            $this->storeVisitClaimRequestByManagerService
                                        ->respondToVisitClaimRequest($storeVisitClaimCode,$validatedData);
            return $request->session()->flash('success','Store visit claim responded successfully  for '.$storeVisitClaimCode.'');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

}
