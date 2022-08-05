<?php
/**
 * Created by PhpStorm.
 * User: Bimal
 * Date: 1/3/2021
 * Time: 12:52 PM
 */

namespace App\Modules\Store\Controllers\Web\Admin\Balance;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Services\StoreService;
use App\Modules\Store\Services\Payment\StoreBalanceControlService;

use App\Modules\Store\Requests\BalanceManagement\StoreBalanceControlRequest;
use Exception;

class AdminStoreBalanceControlController extends BaseController
{

    public $title = 'Balance Control';
    public $base_route = 'admin.store-balance-control.';
    public $sub_icon = 'file';
    public $module = 'Store::';

    private $view='BalanceManagement.';

    private $storeService;
    private $storeBalanceControlService;

    public function __construct(StoreService $storeService,StoreBalanceControlService $storeBalanceControlService)
    {
        $this->middleware('permission:View Store Balance Control List',['only'=>['getAllBalanceControlRequest']]);
        $this->storeService = $storeService;
        $this->storeBalanceControlService = $storeBalanceControlService;
    }

    public function create($storeCode)
    {
        try{
            throw new Exception('Cannot Create Transaction from This sections use Wallet Instead!');

            $store =  $this->storeService->findOrFailStoreByCode($storeCode);
            $current_balance = StoreTransactionHelper::getStoreCurrentBalance($store->store_code);

            return view(Parent::loadViewData($this->module.$this->view.'store-balance-control.create'),compact('store','current_balance'));

        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }

    public function store(StoreBalanceControlRequest $request,$storeCode)
    {
        $validated = $request->validated();
        $validated['store_code'] = $storeCode;

        try{
            throw new Exception('Cannot Create Transaction from This sections use Wallet Instead!');
            $storeBalanceControl =  $this->storeBalanceControlService->saveBalanceControl($validated);

           return $request->session()->flash('success','Balance Control added in Store Code: '.$storeCode.', Transaction Type: '.ucwords($validated['transaction_type']).', Transaction Amount: '.$validated['transaction_amount'].' successfully');

        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }


}
