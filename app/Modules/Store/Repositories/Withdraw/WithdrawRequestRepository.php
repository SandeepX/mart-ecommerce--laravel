<?php
/**
 * Created by PhpStorm.
 * User: sandeep pant
 * Date: 10/22/2020
 * Time: 1:47 PM
 */

namespace App\Modules\Store\Repositories\Withdraw;


use App\Modules\Application\Traits\UploadImage\ImageService;

use App\Modules\Store\Models\Balance\StoreBalanceWithdrawRequest;
use App\Modules\Store\Models\Balance\StoreBalanceWithdrawRequestVerificationDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;


class WithdrawRequestRepository
{
    use ImageService;

    public function saveWithdrawRequestDetail($validatedData){
        return StoreBalanceWithdrawRequest::create($validatedData)->fresh();
    }

    public function getWithdrawRequestByCode($withdrawRequestCode)
    {
       return StoreBalanceWithdrawRequest::where('store_balance_withdraw_request_code',$withdrawRequestCode)
           ->first();
    }

    public function getWithdrawRequestLists($storeCode,$paginatedBy)
    {
        $withdrawRequestLists=StoreBalanceWithdrawRequest::where('store_code',$storeCode)
            ->latest()
            ->paginate($paginatedBy);
        return $withdrawRequestLists;
    }

    public function getWithdrawRequestListDetail($storeCode,$withdrawRequestCode)
    {
        $withdrawRequestList=StoreBalanceWithdrawRequest::where('store_code',$storeCode)
            ->where('store_balance_withdraw_request_code',$withdrawRequestCode)
            ->firstOrFail();

        return $withdrawRequestList;
    }

    public function getWithdrawRequestVerificationDetail($withdrawRequestCode,$paginatedBy=10)
    {
        $withdrawRequestVerificationDetail=StoreBalanceWithdrawRequestVerificationDetail::where('store_balance_withdraw_request_code',$withdrawRequestCode)
            ->paginate($paginatedBy);

        return $withdrawRequestVerificationDetail;
    }
    public function updateWithdrawRequestStatus($withdrawRequest,$validated)
    {
        try {
           $withdrawRequest = $withdrawRequest->update([
                'remarks' => $validated['remarks'],
                'status' => $validated['status']
            ]);
           return $withdrawRequest;
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }

    public function changeVerificationDetailStatus($withdrawRequestVerificationDetail)
    {
        try {
            DB::beginTransaction();
            if($withdrawRequestVerificationDetail->status=="passed")
            {
                $withdrawRequestVerificationDetail->update([
                    'status'=>"failed"
                ]);
            }else{
                $withdrawRequestVerificationDetail->update([
                    'status'=>"passed"
                ]);
            }
            DB::commit();
            return $withdrawRequestVerificationDetail;
        }
        catch (Exception $e)
        {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function getVerificationDetailByCode($withdrawRequestVerificationDetailCode)
    {
        return StoreBalanceWithdrawRequestVerificationDetail::where('withdraw_request_verification_details_code',$withdrawRequestVerificationDetailCode)
            ->firstOrFail();
    }

    public function getWithdrawVerificationDetailsByWithdrawRequestCode($withdrawRequestCode,$withdrawRequestVerificationDetailCode)
    {
         $storeBalanceWithdrawRequestVerificationDetail=StoreBalanceWithdrawRequestVerificationDetail::where('store_balance_withdraw_request_code',$withdrawRequestCode)
                 ->where('status','passed')->whereNotIn('withdraw_request_verification_details_code',[$withdrawRequestVerificationDetailCode])->sum('amount');

         return $storeBalanceWithdrawRequestVerificationDetail;
    }

}
