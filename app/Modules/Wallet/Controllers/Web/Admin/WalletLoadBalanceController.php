<?php

namespace App\Modules\Wallet\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\OfflinePayment\Services\OfflinePaymentService;
use App\Modules\OfflinePayment\Transformers\OfflinePaymentTransformer;
use App\Modules\Store\Services\StoreBalanceReconciliation\StoreBalanceReconciliationService;
use App\Modules\Wallet\Requests\WalletLoadBalanceOfflinePaymentRequest;
use App\Modules\Wallet\Services\WalletLoadBalanceService;
use Exception;

class WalletLoadBalanceController extends BaseController
{
    public $title = 'Wallets Load Balance';
    public $base_route = 'admin.wallets';
    public $sub_icon = 'file';
    public $module = 'Wallet::';
    private $view = 'wallets.load-balance.';

    private $walletLoadBalanceService;
    private $offlinePaymentService;
    private $balanceReconciliationService;
    public function __construct(
        OfflinePaymentService $offlinePaymentService,
        StoreBalanceReconciliationService $balanceReconciliationService,
        WalletLoadBalanceService $walletLoadBalanceService
    ){
        $this->walletLoadBalanceService = $walletLoadBalanceService;
        $this->offlinePaymentService = $offlinePaymentService;
        $this->balanceReconciliationService = $balanceReconciliationService;
    }


    public function showOfflinePaymentDetails($offlinePaymentCode){
         try{
              $offlinePayment = $this->offlinePaymentService->findOrFailOfflinePaymentByCodeWithEager($offlinePaymentCode);
              $balanceReconciliation = $this->balanceReconciliationService->getBalanceReconciliationForVerificationForLoadbalance($offlinePayment);
              $balanceReconciliation = isset($balanceReconciliation) ? $balanceReconciliation : [];

              $offlinePayment = (new OfflinePaymentTransformer($offlinePayment))->transform();
              $balanceReconciliationUsage = $this->balanceReconciliationService->getBalanceReconciliationUsage($offlinePaymentCode);

              return view(Parent::loadViewData($this->module.$this->view.'show'),
                    compact('offlinePayment',
                        'balanceReconciliation',
                        'balanceReconciliationUsage'));
            }catch (Exception $exception){
                return redirect()->back()->with('danger',$exception->getMessage());
            }
    }

    public function respondToOfflinePayment(
        WalletLoadBalanceOfflinePaymentRequest $request,
        $offlinePaymentCode
    ){
        try{
            $validated = $request->validated();
            $this->walletLoadBalanceService->respondToOfflinePaymentByAdmin($validated,$offlinePaymentCode);
            return redirect()->back()->with('success', $this->title .' Offline Payment responded successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function respondToOnlinePayment($onlinePaymentCode){
        try{
            $this->walletLoadBalanceService->respondToOnlinePaymentByAdmin($onlinePaymentCode);
            return redirect()->back()->with('success', $this->title .' Online Payment responded successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }





}
