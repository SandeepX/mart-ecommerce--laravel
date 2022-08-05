<?php


namespace App\Modules\SupportAdmin\Controllers\stores;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Bank\Services\BankService;
use App\Modules\Store\Helpers\StoreBalanceWithdrawRequestHelper;
use App\Modules\Store\Models\Balance\StoreBalanceWithdrawRequest;
use App\Modules\Store\Services\BalanceManagement\BalancewithdrawService;
use App\Modules\Store\Services\StoreService;
use App\Modules\Store\Services\Withdraw\WithdrawRequestService;
use Illuminate\Http\Request;
use Exception;

class StoreWithdrawDetailForSupportAdminController extends BaseController
{
    public $title = 'Store Detail For Admin Support';
    public $base_route = 'support-admin.';
    public $sub_icon = 'file';
    public $module = 'SupportAdmin::';

    private $view = 'stores.store-withdraw.';

    private $storeService;
    private $withdrawRequestService;
    private $withdrawService;
    private $bankService;

    public function __construct(StoreService $storeService,
                                WithdrawRequestService $withdrawRequestService,
                                BalancewithdrawService $withdrawService,
                                BankService $bankService
    )
    {
        $this->storeService =$storeService;
        $this->withdrawRequestService =$withdrawRequestService;
        $this->withdrawService =$withdrawService;
        $this->bankService = $bankService;
    }

    public function getStoreWithdrawDetailForSupportAdmin($storeCode, Request $request)
    {
        try {
            $store = $this->storeService->findStoreByCode($storeCode);
            $response = [];
            $filterData = [
                'store_name' =>$store->store_name
            ];
            $paginatedBy=StoreBalanceWithdrawRequest::RECORDS_PER_PAGE;
            $allwithdrawrequest = StoreBalanceWithdrawRequestHelper::getAllWithdrawRequest($paginatedBy,$filterData);
            $response['html'] = view($this->module . $this->view . 'index',
                compact('storeCode',
                    'allwithdrawrequest','filterData')
               )->render();
            return response()->json($response);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getAllWithdrawRequestOfStore($storeCode)
    {
        try{
            $allwithdrawrequest = $this->withdrawService->getAllwithdrawRequest($storeCode);
            $response['html'] = view($this->module . $this->view . 'all-withdraw-requests',
                compact('storeCode',
                    'allwithdrawrequest')
            )->render();
            return response()->json($response);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function showDetailOfWithdrawRequestOfStore($withdrawRequestCode)
    {
        try{
            $response = [];
            $status=[];
            $withdrawrequestdetail = $this->withdrawService->getwithdrawRequestByStoreCode($withdrawRequestCode);
            $banks=$this->bankService->getAllBanks();
            if($withdrawrequestdetail->status=="pending")
            {
                $status=['processing','rejected'];
            }
            elseif($withdrawrequestdetail->status=="processing")
            {
                $status=['completed','processing'];
            }
            $withdrawRequestVerificationDetail = $this->withdrawRequestService->getWithdrawRequestVerificationDetail($withdrawRequestCode,$paginatedBy=10);
            $pendingAmount=$this->withdrawService->getPendingAmount($withdrawrequestdetail);
            $response['html'] = view($this->module.$this->view.'detail-withdraw-request-modal',
                compact('withdrawrequestdetail',
                    'banks',
                    'pendingAmount',
                    'withdrawRequestVerificationDetail',
                    'status')
            )->render();
            return response()->json($response);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }



    }


}



