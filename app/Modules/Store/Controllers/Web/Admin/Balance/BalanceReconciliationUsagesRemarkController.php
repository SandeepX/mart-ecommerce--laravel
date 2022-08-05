<?php


namespace App\Modules\Store\Controllers\Web\Admin\Balance;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Requests\BalanceReconciliation\BalanceReconciliationUsagesRemarkCreateRequest;
use App\Modules\Store\Services\StoreBalanceReconciliation\BalanceReconciliationUsagesRemakService;
use Exception;
use Illuminate\Http\Request;

class BalanceReconciliationUsagesRemarkController extends BaseController
{
    private $balanceReconciliationUsagesRemakService;
    public function __construct(BalanceReconciliationUsagesRemakService $balanceReconciliationUsagesRemakService){

       $this->balanceReconciliationUsagesRemakService =  $balanceReconciliationUsagesRemakService;

    }

    public function updateRemarks(BalanceReconciliationUsagesRemarkCreateRequest $request, $balanceReconciliationUsagesCode){

        try{
            $validatedData = $request->validated();

           // dd($validatedData);
            $this->balanceReconciliationUsagesRemakService->createBalanceReconciliationUsagesRemark($balanceReconciliationUsagesCode,$validatedData);
            return redirect()->back()->with('success', 'Balance Reconciliation Remarks updated successfully!');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

}
