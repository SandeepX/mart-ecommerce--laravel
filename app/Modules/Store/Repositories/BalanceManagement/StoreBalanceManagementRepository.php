<?php
/**
 * Created by PhpStorm.
 * User: sandeep pant
 * Date: 10/22/2020
 * Time: 1:47 PM
 */

namespace App\Modules\Store\Repositories\BalanceManagement;


use App\Modules\Application\Traits\UploadImage\ImageService;

use App\Modules\Store\Models\Balance\StoreBalanceWithdrawRequestVerificationDetail;
use App\Modules\Store\Models\Payments\StoreBalanceMaster;
use App\Modules\Store\Models\Balance\StoreBalanceWithdrawRequest;
use App\Modules\Store\Models\PreOrder\StoreBalancePreOrderDetail;
use App\Modules\Store\Models\StoreBalanceSalesDetail;
use App\Modules\Store\Models\StoreBalanceSalesReturnDetail;
use Carbon\Carbon;
use Exception;
use DB;


class StoreBalanceManagementRepository
{

    use ImageService;

    public function getAllwithdrawRequest($storeCode){

        return StoreBalanceWithdrawRequest::orderBy('created_at','DESC')
            ->where('store_code',$storeCode)
            ->paginate(10);
    }

    public function getAllwithdrawRequestBystoreCode($withdraw_request_code)
    {
        //dd($store_code);
        return StoreBalanceWithdrawRequest::findorfail($withdraw_request_code);
    }

    public function respondToWithdrawRequest($withdrawRequest,$withdraw_request_code)
    {
       //dd($withdrawRequest['status']);
        $requestwithdrawdata  = StoreBalanceWithdrawRequest::findorfail($withdraw_request_code);

        $requestwithdrawdata->update([
            'status' => $withdrawRequest['status'],
            'remarks' => $withdrawRequest['remarks'],
            'verified_by' => getAuthUserCode(),
            'verified_at' => Carbon::now(),

        ]);


        return $requestwithdrawdata;
    }

    public function respondToWithdrawRequestCompleted($updatewithdrawData, $withdraw_request_code)
    {
       $filename = '';
       try{

           $requestWithdrawData  = StoreBalanceWithdrawRequest::findorfail($withdraw_request_code);
           $image = $updatewithdrawData['document'];

           $filename = $this->storeImageInServer($image,StoreBalanceWithdrawRequest::DOCUMENT_PATH);


           $requestWithdrawData->update([
               'status' => $updatewithdrawData['status'],
               'remarks' => $updatewithdrawData['remarks'],
               'verified_by' =>getAuthUserCode(),
               'verified_at' => Carbon::now(),
               'withdraw_date' => Carbon::now(),
               'document' => $filename,

           ]);

           return $requestWithdrawData;
       }catch (Exception $exception){

           $this->deleteImageFromServer($filename,StoreBalanceWithdrawRequest::DOCUMENT_PATH);
           throw $exception;
       }
    }

    public function saveTransaction($balanceMaster)
    {
        try{
            throw new Exception('Cannot Create Transaction from This sections use Wallet Instead!');
            return StoreBalanceMaster::create($balanceMaster)->fresh();
        }catch (Exception $exception){
            throw $exception;
        }

    }


    public function getAllStoreTransactionFromBalanceMaster()
    {
        //dd('good');
        $allTransactionofStoreGrouped= StoreBalanceMaster::selectRaw('store_code, max(created_at) as created_at')
                                        ->groupBy('store_code')
                                         ->paginate(15);

        return $allTransactionofStoreGrouped;

    }

//    Public function getAllStoresTransactionFromBalanceMasterByStoreCode($store_code)
//    {
//
//        $allTransactionofStore = StoreBalanceMaster::where('store_code',$store_code)->orderBy('created_at','DESC')->paginate(15);
//       // dd($allTransactionofStore);
//        return $allTransactionofStore;
//
//    }

    public function saveStoreBalanceSalesDetails($storeBalanceSalesData){
        return StoreBalanceSalesDetail::create($storeBalanceSalesData)->fresh();
    }

    public function saveStoreBalanceSalesReturnDetail($storeBalanceSalesReturnData){
       return StoreBalanceSalesReturnDetail::create($storeBalanceSalesReturnData)->fresh();
    }

    public function saveStoreBalancePreOrderDetail($validatedData){
        return StoreBalancePreOrderDetail::create($validatedData)->fresh();
    }

  public function getAllwithdrawRequestMadeByStore($storeCode, $paginatedBy){
      return StoreBalanceWithdrawRequest::where('store_code',$storeCode)->paginate($paginatedBy);
  }


   public function storeVerificationDetail($value,$withdrawrequestdetail)
   {

       $value['store_balance_withdraw_request_code'] = $withdrawrequestdetail->store_balance_withdraw_request_code;
       $value['payment_body_code'] = $withdrawrequestdetail->payment_body_code;
       $value['payment_method'] = "bank";
       $value['proof']= $this->storeImageInServer($value['proof'], StoreBalanceWithdrawRequestVerificationDetail::DOCUMENT_PATH);
       return StoreBalanceWithdrawRequestVerificationDetail::create($value)->fresh();
   }

    public function getPendingAmount($withdrawRequest)
    {

        $verifiedAmount=StoreBalanceWithdrawRequestVerificationDetail::where(
            'store_balance_withdraw_request_code',$withdrawRequest->store_balance_withdraw_request_code)
                       // ->select(DB::raw('SUM(amount) as verified_amount'))
                        ->where('status','passed')
                        ->sum('amount');
        $pendingAmount=roundPrice($withdrawRequest->requested_amount - $verifiedAmount);

        return $pendingAmount;
    }
}
