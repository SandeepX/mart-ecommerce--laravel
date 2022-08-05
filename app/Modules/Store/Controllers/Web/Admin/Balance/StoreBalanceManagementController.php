<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/19/2020
 * Time: 12:52 PM
 */

namespace App\Modules\Store\Controllers\Web\Admin\Balance;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Location\Services\LocationHierarchyService;
use App\Modules\Store\Helpers\StoreBalanceHelper;
use App\Modules\Store\Models\Payments\StoreBalanceMaster;

use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Exports\Balance\BalanceExport;
use App\Modules\Store\Services\StoreService;
use Illuminate\Http\Request;
use Exception;
use DB;


class StoreBalanceManagementController extends BaseController
{

    public $title = 'Stores Balance Management';
    public $base_route = 'admin.store.balance.';
    public $sub_icon = 'file';
    public $module = 'Store::';

    private $view='BalanceManagement.';

    private $storeService,$locationHierarchyService;

    public function __construct(
        StoreService $storeService,
        LocationHierarchyService $locationHierarchyService
    )
    {
        $this->middleware('permission:View Store Balance Withdraw List',['only'=>['getAllWithdrawRequest']]);
        $this->middleware('permission:View Store Balance Withdraw List',['only'=>['getwithdrawdetailById']]);
        $this->middleware('permission:View Store Balance Withdraw List',['only'=>['verifywithdrawRequest']]);
        $this->middleware('permission:View Store Balance Withdraw List',['only'=>['getAllBalanceDetail']]);
        $this->middleware('permission:View Store Balance Withdraw List',['only'=>['getAllBalanceDetailOfStore']]);

        $this->storeService = $storeService;
        $this->locationHierarchyService = $locationHierarchyService;
    }

    public function getStoreBalances(Request $request)
    {

        $filterParameters = [
            'store_name' =>$request->get('store_name'),
            'current_balance_order'=>$request->get('current_balance_order'),
            'province' => $request->get('province'),
            'district' => $request->get('district'),
            'municipality' => $request->get('municipality'),
            'ward' => $request->get('ward'),
            'records_per_page' => 20
        ];
        $storeBalances = StoreBalanceHelper::filterPaginatedStoresForAdmin($filterParameters,$filterParameters['records_per_page']);
        $provinces = $this->locationHierarchyService->getAllLocationsByType('province');
        return view(Parent::loadViewData( $this->module.$this->view.'Balance.index'),
            compact('storeBalances','filterParameters','provinces')
        );

    }


    public function getStoreBalanceDetail(Request $request,$store_code)
    {
         try{
            $filterParameters = [
              'store_code' =>$store_code,
              'transaction_type' => $request->get('transaction_type'),
              'transaction_date_from' => $request->get('transaction_date_from'),
              'transaction_date_to' => $request->get('transaction_date_to'),
            ];
            $store = $this->storeService->findOrFailStoreByCode($store_code);
            $transactionTypes = StoreBalanceMaster::TRANSACTION_TYPE;
            $storeTotalBalance = StoreTransactionHelper::getLatestStoreCumulativeBalance($filterParameters['store_code']);
            $totalFreezeAmountDetails = StoreTransactionHelper::getStoreFreezeAmountDetails($filterParameters['store_code']);
            $storeActiveBalance = roundPrice($storeTotalBalance - $totalFreezeAmountDetails['total_freeze_amount']);
            $allTransactionByStoreCode = StoreTransactionHelper::adminPanelStoreAllTransactionByParameters($filterParameters);



          return view(Parent::loadViewData($this->module.$this->view.'Balance.detailTransaction'),
              compact('allTransactionByStoreCode',
                  'store','storeActiveBalance','transactionTypes','filterParameters',
                  'totalFreezeAmountDetails','storeTotalBalance'));
      }catch (Exception $e){
          return redirect()->route('admin.dashboard')->with('danger',$e->getMessage());
      }


    }

    public function exportBalance(Request $request)
    {
        try{
            $filterParameters = [
                'store_name' =>$request->get('store_name'),
                'current_balance_order'=>$request->get('current_balance_order'),
                'province' => $request->get('province'),
                'district' => $request->get('district'),
                'municipality' => $request->get('municipality'),
                'ward' => $request->get('ward'),
            ];

            $storeBalances = StoreBalanceHelper::filterPaginatedStoresForAdmin($filterParameters, null);
            return (new BalanceExport($storeBalances));
        }
        catch (Exception $e) {
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }


}
