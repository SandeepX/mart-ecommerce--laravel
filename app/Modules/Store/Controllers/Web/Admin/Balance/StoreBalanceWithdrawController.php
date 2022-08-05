<?php
/**
 * Created by PhpStorm.
 * User: Bimal
 * Date: 01/27/2021
 * Time: 12:52 PM
 */

namespace App\Modules\Store\Controllers\Web\Admin\Balance;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Bank\Models\Bank;
use App\Modules\Bank\Services\BankService;
use App\Modules\Store\Helpers\StoreBalanceWithdrawRequestHelper;
use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Models\Balance\StoreBalanceWithdrawRequest;
use App\Modules\Store\Models\Balance\StoreBalanceWithdrawRequestVerificationDetail;
use App\Modules\Store\Requests\BalanceManagement\BalancewithdrawRespond;
use App\Modules\Store\Requests\Withdraw\AddVerificationDetailRequest;
use App\Modules\Store\Services\BalanceManagement\BalancewithdrawService;

use App\Modules\Store\Services\Withdraw\WithdrawRequestService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class StoreBalanceWithdrawController extends BaseController
{

    public $title = 'Stores Balance Withdraw Request';
    public $base_route = 'admin.balance.';
    public $sub_icon = 'file';
    public $module = 'Store::';

    private $view='BalanceManagement.';

    private $withdrawService,$withdrawRequestService,$bankService;

    public function __construct(BalancewithdrawService $withdrawService,
                                WithdrawRequestService $withdrawRequestService,
                                BankService $bankService
    )
    {
        $this->middleware('permission:View Store Balance Withdraw List',['only'=>['getAllWithdrawRequestOfStore']]);
        $this->middleware('permission:View Store Balance Withdraw Detail',['only'=>['getwithdrawdetailById']]);

        $this->withdrawService = $withdrawService;
        $this->withdrawRequestService = $withdrawRequestService;
        $this->bankService = $bankService;
    }

    public function getAllWithdrawRequestOfStore($storeCode)    {

        $allwithdrawrequest = $this->withdrawService->getAllwithdrawRequest($storeCode);

        return view(Parent::loadViewData($this->module.$this->view.'Requestwithdraw.withdraw-request-lists-store'),
            compact('allwithdrawrequest'));
    }

    public function getwithdrawdetailById($withdraw_request_code)
    {
        $status=[];
        $withdrawrequestdetail = $this->withdrawService->getwithdrawRequestByStoreCode($withdraw_request_code);
        $banks=$this->bankService->getAllBanks();
        if($withdrawrequestdetail->status=="pending")
        {
            $status=['processing','rejected'];
        }
        elseif($withdrawrequestdetail->status=="processing")
        {
            $status=['completed','processing'];
        }
        $withdrawRequestVerificationDetail = $this->withdrawRequestService->getWithdrawRequestVerificationDetail($withdraw_request_code,$paginatedBy=10);
        $pendingAmount=$this->withdrawService->getPendingAmount($withdrawrequestdetail);
        return view(Parent::loadViewData($this->module.$this->view.'Requestwithdraw.verify'),compact('withdrawrequestdetail','banks','pendingAmount','withdrawRequestVerificationDetail','status'));
    }

    public function respondToWithdrawRequest(BalancewithdrawRespond $request,$withdraw_request_code)
    {
        try{
            $validated = $request->validated();
            $withdrawrequestdetail = $this->withdrawService->getwithdrawRequestByStoreCode($withdraw_request_code);

            if(($validated['status'] == "completed" || $validated['status'] == "processing"))
            {
                if(isset($validated['addmore']) && count($validated['addmore']) >0)
                {
                  $validatedDetailConsistsData=array_column($validated['addmore'],'payment_verification_source');
                  $validatedDetailConsistsData= array_filter($validatedDetailConsistsData);
                    if(count($validatedDetailConsistsData) > 0){
                        $verificationDetail = $this->withdrawService->storeVerificationDetail($validated['addmore'],$withdraw_request_code,$withdrawrequestdetail);
                    }
                }
            }

            $withdrawRequest = $this->withdrawService->changeWithdrawRequestStatus($validated,$withdraw_request_code);

            return redirect()->back()->with('success', $this->title .'('.$withdrawRequest->store_balance_withdraw_request_code.')'.' responded successfully');

        }catch (Exception $exception){
            DB::rollback();
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }

    }
//    done by Govinda
    public function VerificationDetailForm($withdrawRequestCode)
    {
        $banks=$this->bankService->getAllBanks();
        return view(Parent::loadViewData($this->module.$this->view.'Requestwithdraw.verification-detail-form'),compact('withdrawRequestCode','banks'));
    }

    public function getAllWithdrawRequest(Request $request)
    {
        try{
            $filterData = [
                'store_name' =>$request->get('store_name')
            ];

            $paginatedBy=StoreBalanceWithdrawRequest::RECORDS_PER_PAGE;
            $allwithdrawrequest = StoreBalanceWithdrawRequestHelper::getAllWithdrawRequest($paginatedBy,$filterData);

            return view(Parent::loadViewData($this->module.$this->view.'Requestwithdraw.index-two'),
                compact('allwithdrawrequest','filterData'));
        }catch(Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger',$exception->getMessage());
        }

    }

    public function storeVerificationDetail($validated,$withdrawRequestCode)
    {

        try{
            $withdrawrequestdetail = $this->withdrawService->getwithdrawRequestByStoreCode($withdrawRequestCode);
            $verificationDetail = $this->withdrawService->storeVerificationDetail($validated['addmore'],$withdrawRequestCode,$withdrawrequestdetail);

            return redirect()->back()->with('success', $this->title .' Verification detail added successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }
    public function changeWithdrawVerificationDetailStatus($withdrawRequestVerificationDetailCode)
    {
        try{
            $withdrawRequestVerificationDetail=$this->withdrawService->changeVerificationDetailStatus($withdrawRequestVerificationDetailCode);

            return redirect()->back()->with('success','Status updated Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }

    }
}
