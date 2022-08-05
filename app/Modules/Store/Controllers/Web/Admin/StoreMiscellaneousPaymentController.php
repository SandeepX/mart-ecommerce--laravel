<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/29/2020
 * Time: 12:59 PM
 */

namespace App\Modules\Store\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\Questionnaire\Exceptions\QuestionnaireVerificationException;
use App\Modules\Questionnaire\Helpers\ActionVerificationQuestionsHelper;
use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Store\Helpers\StoreMiscPaymentHelper;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPaymentView;
use App\Modules\Store\Models\Payments\StoreBalanceMaster;
use App\Modules\Store\Requests\Payment\StoreMiscPaymentRespondRequest;
use App\Modules\Store\Requests\Payment\UpdateStoreMiscellaneousPaymentRequest;
use App\Modules\Store\Services\Payment\StorePaymentService;
use App\Modules\Store\Services\StoreBalanceReconciliation\StoreBalanceReconciliationService;
use App\Modules\Store\Helpers\StoreTransactionHelper;

use App\Modules\Store\Services\StoreService;
use App\Modules\Store\Transformers\StoreMiscellaneousPaymentTransformer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StoreMiscellaneousPaymentController extends BaseController
{

    public $title = 'Stores Miscellaneous Payments';
    public $base_route = 'admin.stores.misc-payments.';
    public $sub_icon = 'file';
    public $module = 'Store::';

    private $view='admin.store-payment.misc.';

    private $paymentService,$storeService,$balanceReconciliationService;
    private $storeBalance;

    public function __construct(StorePaymentService $paymentService,
                                StoreService$storeService,
                                StoreBalanceReconciliationService $balanceReconciliationService,
                                StoreBalance $storeBalance
    ){
        $this->middleware('permission:View Store Miscellaneous Payment List', ['only' => ['getAllStoreMiscPaymentsByUsingGroupBy']]);
        $this->middleware('permission:View Store Miscellaneous Payment List',['only'=>['showdetailStoreMiscPaymentByUserCode']]);

        $this->middleware('permission:Show Store Miscellaneous Payment', ['only' => ['showStoreMiscPayment']]);
        $this->middleware('permission:Verify Store Miscellaneous Payment', ['only' => ['showStoreMiscPayment','respondToStoreMiscPayment']]);

        $this->paymentService = $paymentService;
        $this->storeService = $storeService;
        $this->balanceReconciliationService = $balanceReconciliationService;
        $this->storeBalance = $storeBalance;
    }

     public function getAllStoreMiscPayments(Request $request){

         try{
             throw new Exception('Misc Payment has shifted to offline payments so respond from there :(');
             $filterParameters = [
                 'store_name' =>$request->get('store_name'),
                 'amount_condition' => $request->get('amount_condition'),
                 'payment_for' => $request->get('payment_for'),
                 'amount' => $request->get('amount'),
                 'last_verification_status'=>$request->get('last_verification_status')
             ];

             //dd($filterParameters);
             $paymentsFor = StoreMiscellaneousPaymentView::PAYMENT_FOR;
             $verificationStatuses=StoreMiscellaneousPaymentView::VERIFICATION_STATUSES;

             //dd($paymentTypes);
             $amountConditions=[
                 'Greater Than >'=>'>',
                 'Less Than <'=>'<' ,
                 'Greater Than & Equal To >='=>'>=' ,
                 'Less Than & Equal To <='=>'<=',
                 'Equal To ='=>'=',
             ];
             $with =[
                 'store','submittedBy'
             ];
             $storePayments = StoreMiscPaymentHelper::filterStoreMiscPaymentByParameters($filterParameters,$with);
            // $storePayments = StoreMiscPaymentHelper::filterPaginatedStoreMiscPaymentByParameters($filterParameters,StoreMiscellaneousPayment::RECORDS_PER_PAGE,$with);

             return view(Parent::loadViewData($this->module.$this->view.'index'),
                 compact('storePayments','filterParameters','verificationStatuses','amountConditions','paymentsFor'));

         }catch (Exception $e){
             return redirect()->back()->with('danger',$e->getMessage());
         }
     }


    public function getAllStoreMiscPaymentsByUsingGroupBy(Request $request){
        //dd(1);
        try{
            throw new Exception('Misc Payment has shifted to offline payments so respond from there :(');
            $filterParameters = [
                'store_name' =>$request->get('store_name'),
                'verification_status' => $request->get('verification_status'),
                'payment_type' => $request->get('payment_type'),
                'payment_date_from' => $request->get('payment_date_from'),
                'payment_date_to' => $request->get('payment_date_to'),
                'amount_condition' => $request->get('amount_condition'),
                'amount' => $request->get('amount'),
            ];
            //$stores = $this->storeService->getAllStores();
            $verificationStatuses=StoreMiscellaneousPayment::VERIFICATION_STATUSES;
            //dd($verificationStatuses);
            $paymentTypes = StoreMiscellaneousPayment::PAYMENT_TYPE;
            $amountConditions=[
                'Greater Than >'=>'>',
                'Less Than <'=>'<' ,
                'Greater Than & Equal To >='=>'>=' ,
                'Less Than & Equal To <='=>'<=',
                'Equal To ='=>'=',
            ];
            $with =[
                'store','submittedBy'
            ];
          //  dd($filterParameters);
            //$storePayments = StoreMiscPaymentHelper::filterStoreMiscPaymentByParameters($filterParameters,$with);
            $storePayments = StoreMiscPaymentHelper::filterPaginatedStoreMiscPaymentByParametersUsingGroupBy($filterParameters,StoreMiscellaneousPayment::RECORDS_PER_PAGE,$with);

           // dd($storePayments);
            $payment_status = array();
            foreach($storePayments as $key => $value){
                $data['created_at'] = $value->created_at;
                $paymentdetail = StoreMiscellaneousPayment::where('created_at',$data)->get();
                $payment_status[] = $paymentdetail[0]->verification_status;
            }

            return view(Parent::loadViewData($this->module.$this->view.'index'),
                compact('storePayments','payment_status','filterParameters','verificationStatuses','paymentTypes','amountConditions'));

        }catch (Exception $e){
            return redirect()->route('admin.dashboard')->with('danger',$e->getMessage());
        }
    }

    public function showStoreMiscPayment($miscPaymentCode){
        try{

            $storePayment = $this->paymentService->findOrFailStoreMiscellaneousPaymentByCodeWithEager($miscPaymentCode);

            if(!in_array($storePayment->payment_for,['load_balance','initial_registration'])){
                throw new Exception('Only load Balance Can be handled from here. For others payment visit offline payments');
            }

            throw new Exception('Misc Payment has shifted to offline payments so respond from there :(');
            $balanceReconciliation = $this->balanceReconciliationService->getBalanceReconciliationForVerificationForLoadbalance($storePayment);

          //  $currentBalance = StoreTransactionHelper::getStoreCurrentBalance($storePayment->store_code);
            $store = $this->storeService->findOrFailStoreByCode($storePayment->store_code);
            $currentBalance = $this->storeBalance->getStoreActiveBalance($store);
            $storePayment = (new StoreMiscellaneousPaymentTransformer($storePayment))->transform();

            $balanceReconciliationUsage = $this->balanceReconciliationService->getBalanceReconciliationUsage($miscPaymentCode);
         //   dd($balanceReconciliationUsage);

            return view(Parent::loadViewData($this->module.$this->view.'show'),
                compact('storePayment','balanceReconciliation','currentBalance','balanceReconciliationUsage'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function showMiscPaymentsByStoreAndPaymentFor(Request $request,$storeCode,$paymentFor){

        try{

            throw new Exception('Misc Payment has shifted to offline payments so respond from there :(');

            $filterParameters = [
                'payment_code' =>$request->payment_code,
                'payment_type' => $request->payment_type,
                'amount_condition' => $request->get('amount_condition'),
                'amount'=>$request->amount,
                'payment_date_from'=>$request->payment_date_from,
                'payment_date_to' =>$request->payment_date_to,
                'payment_status' => $request->payment_status,
            ];
            $amountConditions=[
                'Greater Than >'=>'>',
                'Less Than <'=>'<' ,
                'Greater Than & Equal To >='=>'>=' ,
                'Less Than & Equal To <='=>'<=',
                'Equal To ='=>'=',
            ];
            $paymentsTypes = StoreMiscellaneousPayment::PAYMENT_TYPE;
            $paymentStatus = StoreMiscellaneousPayment::VERIFICATION_STATUSES;
            $with =['submittedBy','respondedBy','paymentDocuments','paymentMetaData'];

            $store = $this->storeService->findOrFailStoreByCode($storeCode);
            $storePayments = StoreMiscPaymentHelper::filterAllMiscPaymentsByStoreCodeAndPaymentType($filterParameters,$store->store_code,$paymentFor,StoreMiscellaneousPayment::RECORDS_PER_PAGE,$with);

           // dd($storePayment);

            return view(Parent::loadViewData($this->module.$this->view.'detaillog'),
                compact('storePayments','store','filterParameters','paymentFor','paymentsTypes','amountConditions','paymentStatus'));
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger',$exception->getMessage());
        }
    }

    public function showMatchedMiscPaymentsByStoreAndPaymentFor(Request $request,$storeCode,$paymentFor){

        try{
            throw new Exception('Misc Payment has shifted to offline payments so respond from there :(');
            $filterParameters = [
                'payment_code' =>$request->payment_code,
                'payment_type' => $request->payment_type,
                'amount_condition' => $request->get('amount_condition'),
                'amount'=>$request->amount,
                'payment_date_from'=>$request->payment_date_from,
                'payment_date_to' =>$request->payment_date_to,
                'payment_status' => $request->payment_status,
                'has_matched' => 1
            ];
            $amountConditions=[
                'Greater Than >'=>'>',
                'Less Than <'=>'<' ,
                'Greater Than & Equal To >='=>'>=' ,
                'Less Than & Equal To <='=>'<=',
                'Equal To ='=>'=',
            ];
            $paymentsTypes = StoreMiscellaneousPayment::PAYMENT_TYPE;
            $paymentStatus = StoreMiscellaneousPayment::VERIFICATION_STATUSES;
            $with =['submittedBy','respondedBy','paymentDocuments','paymentMetaData'];

            $store = $this->storeService->findOrFailStoreByCode($storeCode);
            $storePayments = StoreMiscPaymentHelper::filterAllMiscPaymentsByStoreCodeAndPaymentType($filterParameters,$store->store_code,$paymentFor,StoreMiscellaneousPayment::RECORDS_PER_PAGE,$with);


            return view(Parent::loadViewData($this->module.$this->view.'matchedlog'),
                compact('storePayments','store','filterParameters','paymentFor','paymentsTypes','amountConditions','paymentStatus'));
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger',$exception->getMessage());
        }
    }

    public function showUnMatchedMiscPaymentsByStoreAndPaymentFor(Request $request,$storeCode,$paymentFor){

        try{
            throw new Exception('Misc Payment has shifted to offline payments so respond from there :(');

            $filterParameters = [
                'payment_code' =>$request->payment_code,
                'payment_type' => $request->payment_type,
                'amount_condition' => $request->get('amount_condition'),
                'amount'=>$request->amount,
                'payment_date_from'=>$request->payment_date_from,
                'payment_date_to' =>$request->payment_date_to,
                'payment_status' => $request->payment_status,
                'has_matched' => 0
            ];
            $amountConditions=[
                'Greater Than >'=>'>',
                'Less Than <'=>'<' ,
                'Greater Than & Equal To >='=>'>=' ,
                'Less Than & Equal To <='=>'<=',
                'Equal To ='=>'=',
            ];
            $paymentsTypes = StoreMiscellaneousPayment::PAYMENT_TYPE;
            $paymentStatus = StoreMiscellaneousPayment::VERIFICATION_STATUSES;
            $with =['submittedBy','respondedBy','paymentDocuments','paymentMetaData'];

            $store = $this->storeService->findOrFailStoreByCode($storeCode);
            $storePayments = StoreMiscPaymentHelper::filterAllMiscPaymentsByStoreCodeAndPaymentType($filterParameters,$store->store_code,$paymentFor,StoreMiscellaneousPayment::RECORDS_PER_PAGE,$with);

            // dd($storePayment);

            return view(Parent::loadViewData($this->module.$this->view.'unmatchedlog'),
                compact('storePayments','store','filterParameters','paymentFor','paymentsTypes','amountConditions','paymentStatus'));
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger',$exception->getMessage());
        }
    }




    public function respondToStoreMiscPayment(StoreMiscPaymentRespondRequest $request,$miscPaymentCode)
    {
        try{
            throw new Exception('Misc Payment has shifted to offline payments so respond from there :(');
               $validated = $request->validated();
               $validated['questions_checked_meta'] = NULL;
               if($validated['verification_status'] == 'verified'){
                   $validated['questions_checked_meta'] =  ActionVerificationQuestionsHelper::validateActionVerificationQuestions(
                       $request,'balance','miscellaneous_payment_verification'
                   );
               }

//               if($this->paymentService->isPaymentForInitialRegistration($miscPaymentCode)) {
//                   $this->paymentService->respondToStoreInitialRegistrationMiscPaymentByAdmin($validated,$miscPaymentCode);
//               }else{
//                   $this->paymentService->respondToStoreMiscPaymentByAdmin($validated,$miscPaymentCode);
//               }
               $this->paymentService->respondToStoreMiscPaymentByAdmin($validated,$miscPaymentCode);
               return redirect()->back()->with('success', $this->title .' responded successfully');
            }catch (Exception $exception){
            if($exception instanceof QuestionnaireVerificationException){
                return redirect()->back()
                           ->withErrors($exception->getData()['validator'])
                           ->withInput();
            }
                return redirect()->back()->with('danger', $exception->getMessage())->withInput();
            }


    }

    public function editStoreMiscPayment($storePaymentCode)
    {
        try{
            throw new Exception('Misc Payment has shifted to offline payments so respond from there :(');
           $storePayment =  $this->paymentService->findOrFailStoreMiscellaneousPaymentByCodeWithEager($storePaymentCode);
           $transactionNumbers = $storePayment->paymentMetaData()->where('key','transaction_number')->get();
           $remarks = $storePayment->paymentMetaData()->where('key','remark')->first();
           $adminDescription = $storePayment->paymentMetaData()->where('key','admin_description')->first();
            return view(Parent::loadViewData($this->module.$this->view.'edit'),
                compact('storePayment','transactionNumbers','remarks','adminDescription'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function updateStoreMiscPayment(UpdateStoreMiscellaneousPaymentRequest $request,$storePaymentCode)
    {
        try{
            throw new Exception('Misc Payment has shifted to offline payments so respond from there :(');
            DB::beginTransaction();
            $validated =  $request->validated();
            $this->paymentService->adminUpdateMiscPayment($storePaymentCode,$validated);
            DB::commit();
            return $request->session()->flash('success',' Miscellaneous Payment ('.$storePaymentCode.') Updated Successfully');
        }catch (Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }


}
