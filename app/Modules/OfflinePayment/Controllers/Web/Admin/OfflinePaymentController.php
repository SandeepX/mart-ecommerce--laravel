<?php


namespace App\Modules\OfflinePayment\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\OfflinePayment\Helpers\OfflinePaymentHelper;
use App\Modules\OfflinePayment\Helpers\OfflinePaymentsFilterHelper;
use App\Modules\OfflinePayment\Models\OfflinePaymentMaster;
use App\Modules\OfflinePayment\Requests\OfflinePaymentRespondRequest;
use App\Modules\OfflinePayment\Requests\UpdateOfflinePaymentRequest;
use App\Modules\OfflinePayment\Services\OfflinePaymentService;
use App\Modules\OfflinePayment\Transformers\OfflinePaymentTransformer;
use App\Modules\PaymentGateway\Helpers\OnlinePaymentHelper;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;
use App\Modules\Store\Requests\Payment\UpdateStoreMiscellaneousPaymentRequest;
use App\Modules\Store\Services\Payment\StorePaymentService;
use App\Modules\Store\Services\StoreBalanceReconciliation\StoreBalanceReconciliationService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class OfflinePaymentController extends BaseController
{
    public $title = 'Offline Payments';
    public $base_route = 'admin.offline-payment.';
    public $sub_icon = 'file';
    public $module = 'OfflinePayment::';
    public $view = 'admin.offline-payments.';

    public $paymentService;
    public $balanceReconciliationService;
    public $offlinePaymentService;


    public function __construct(StorePaymentService $paymentService,
                                 StoreBalanceReconciliationService $balanceReconciliationService,
                                 OfflinePaymentService $offlinePaymentService
    )
    {
        $this->paymentService = $paymentService;
        $this->balanceReconciliationService = $balanceReconciliationService;
        $this->offlinePaymentService = $offlinePaymentService;
    }

    public function index(Request $request)
    {
        try{
            $filterParameters = [
                'payment_code' =>$request->payment_code,
                'payment_type' => $request->payment_type,
                'amount_condition' => $request->get('amount_condition'),
                'amount'=>$request->amount,
                'payment_date_from'=>$request->payment_date_from,
                'payment_date_to' =>$request->payment_date_to,
                'payment_status' => $request->payment_status,
                'has_matched' => $request->has_matched,
                'payment_for' => $request->payment_for,
                'user_type' => $request->user_type,
            ];

            $amountConditions=[
                'Greater Than >'=>'>',
                'Less Than <'=>'<' ,
                'Greater Than & Equal To >='=>'>=' ,
                'Less Than & Equal To <='=>'<=',
                'Equal To ='=>'=',
            ];

            $paymentFor = StoreMiscellaneousPayment::PAYMENT_FOR;
            $paymentsTypes = StoreMiscellaneousPayment::PAYMENT_TYPE;
            $paymentStatus = StoreMiscellaneousPayment::VERIFICATION_STATUSES;
            $with =['submittedBy','respondedBy','paymentDocuments','paymentMetaData'];

            $allOfflinePayments = OfflinePaymentsFilterHelper::filterAllOfflinePaymentsByPaymentType($filterParameters,20,$with);

            return view(Parent::loadViewData($this->module.$this->view.'index'),
                compact('allOfflinePayments',
                'filterParameters',
                    'amountConditions',
                    'paymentFor',
                    'paymentsTypes',
                    'paymentStatus')
            );
        }catch (Exception $e){
            return redirect()->route('admin.dashboard')->with('danger',$e->getMessage());
        }
    }

    public function getOfflinePaymentLists(Request $request,$paymentHolderType,$paymentFor){
        try{
            $filterParameters = [
                'payment_code' =>$request->payment_code,
                'payment_type' => $request->payment_type,
                'amount_condition' => $request->get('amount_condition'),
                'amount'=> $request->amount,
                'payment_date_from'=>$request->payment_date_from,
                'payment_date_to' =>$request->payment_date_to,
                'payment_status' => $request->payment_status,
                'has_matched' => $request->has_matched,
                'payment_for' => $paymentFor,
                'user_type' => $request->user_type,
                'payment_holder_type' => $paymentHolderType
            ];

            $amountConditions=[
                'Greater Than >'=>'>',
                'Less Than <'=>'<' ,
                'Greater Than & Equal To >='=>'>=' ,
                'Less Than & Equal To <='=>'<=',
                'Equal To ='=>'=',
            ];

            if($paymentFor != 'load_balance'){
              throw new Exception(" $paymentFor is not handled form here!");
            }

            $paymentFor = OfflinePaymentMaster::PAYMENT_FOR;
            $paymentsTypes = OfflinePaymentMaster::PAYMENT_TYPE;
            $paymentStatus = OfflinePaymentMaster::VERIFICATION_STATUSES;

            $allOfflinePayments = OfflinePaymentsFilterHelper::getAllOfflinePaymentsByFilterParameters($filterParameters,20);
             $allOfflinePayments->getCollection()->transform(function ($offlinePayment,$key){
                 $offlinePayment->payment_holder_name = OfflinePaymentHelper::getOfflinePaymentHolderName($offlinePayment);
                 return $offlinePayment;
             });

            return view(Parent::loadViewData($this->module.$this->view.'detail-payment-lists'),
                compact('allOfflinePayments',
                    'filterParameters',
                    'amountConditions',
                    'paymentFor',
                    'paymentsTypes',
                    'paymentStatus'
                )
            );
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger',$exception->getMessage());
        }
    }


    public function show($offlinePaymentCode)
    {
        try{
            throw new Exception('offline payment this feature not available');
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

    public function edit($offlinePaymentCode)
    {
        try{
            $offlinePayment =  $this->offlinePaymentService->findOrFailOfflinePaymentByCodeWithEager($offlinePaymentCode);
            $transactionNumbers = $offlinePayment->paymentMetaData()->where('key','transaction_number')->get();
            $remarks = $offlinePayment->paymentMetaData()->where('key','remark')->first();
            $adminDescription = $offlinePayment->paymentMetaData()->where('key','admin_description')->first();
            return view(Parent::loadViewData($this->module.$this->view.'edit'),
                compact('offlinePayment','transactionNumbers','remarks','adminDescription'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function update(UpdateOfflinePaymentRequest $request,$offlinePaymentCode)
    {
        try{
            DB::beginTransaction();
            $validated =  $request->validated();
            $this->offlinePaymentService->adminUpdateOfflinePayment($offlinePaymentCode,$validated);
            DB::commit();
            return $request->session()->flash('success',' Offline Payment ('.$offlinePaymentCode.') Updated Successfully');
        }catch (Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }


    public function respondToOfflinePayment(OfflinePaymentRespondRequest $request,$SMPCode)
    {
        try{
            throw new Exception('this  feature is unavailabe at the moment');
            $validated = $request->validated();
            $this->offlinePaymentService->respondToOfflinePaymentByAdmin($validated,$SMPCode);
            return redirect()->back()->with('success', $this->title .' responded successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }


}
