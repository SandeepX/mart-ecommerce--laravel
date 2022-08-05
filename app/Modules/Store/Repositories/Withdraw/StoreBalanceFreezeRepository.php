<?php
/**
 * Created by PhpStorm.
 * User: sandeep pant
 * Date: 10/22/2020
 * Time: 1:47 PM
 */

namespace App\Modules\Store\Repositories\Withdraw;


use App\Modules\Application\Traits\UploadImage\ImageService;

use App\Modules\Store\Models\Balance\StoreBalanceFreeze;
use App\Modules\Store\Models\Balance\StoreBalanceWithdrawRequest;
use App\Modules\Store\Models\Balance\StoreBalanceWithdrawRequestVerificationDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;


class StoreBalanceFreezeRepository
{

    use ImageService;

    public function getFrozenBalance($withdrawRequestCode)
    {
        $frozenBalance=StoreBalanceFreeze::where('status',1)->where('source_code',$withdrawRequestCode)->get();
        $frozenAmount=$frozenBalance->sum('amount');

        return $frozenAmount;
    }
    public function saveFreezeBalance($withdrawRequestDetail)
    {
        try {
            DB::beginTransaction();
            $storeCode=getAuthStoreCode();
            $data=[
                'store_code' => $storeCode,
                'amount' => $withdrawRequestDetail->requested_amount,
                'source' => "withdraw",
                'source_code' => $withdrawRequestDetail->store_balance_withdraw_request_code,
            ];
            $freezeBalance=StoreBalanceFreeze::create($data)->fresh();
            DB::commit();
            return $freezeBalance;
        }
        catch (Exception $e)
        {
            DB::rollBack();
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }
   public function changeFreezeStatusFromOneToZero($withdrawRequestCode)
   {
       $freezeBalance=StoreBalanceFreeze::where('source_code',$withdrawRequestCode)
           ->update([
               'status' => 0
           ]);
       return $freezeBalance;
   }

}
