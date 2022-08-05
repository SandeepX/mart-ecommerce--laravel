<?php


namespace App\Modules\SupportAdmin\Controllers\stores;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Store\Helpers\StoreMiscPaymentHelper;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;
use App\Modules\Store\Services\Payment\StorePaymentService;
use App\Modules\Store\Services\StoreService;
use App\Modules\Store\Transformers\StoreMiscellaneousPaymentTransformer;
use Illuminate\Http\Request;
use Exception;

class StorePaymentForSupportAdminController extends BaseController
{
    public $title = 'Store Detail For Admin Support';
    public $base_route = 'support-admin.';
    public $sub_icon = 'file';
    public $module = 'SupportAdmin::';

    private $view = 'stores.store-payment.';

    private $storeService;
    private $paymentService;
    private $storeBalance;

    public function __construct(StoreService $storeService,
                                StorePaymentService $paymentService,
                                StoreBalance $storeBalance

    )
    {
        $this->storeService = $storeService;
        $this->paymentService = $paymentService;
        $this->storeBalance = $storeBalance;
    }

    public function getStorePaymentForSupportAdmin($storeCode, Request $request)
    {
        try {
            $store = $this->storeService->findStoreByCode($storeCode);
            $response = [];
            $filterParameters = [
                'store_name' => $store->store_name
            ];
            $with =[
                'store','submittedBy'
            ];
            $storePayments = StoreMiscPaymentHelper::filterStoreMiscPaymentByParameters($filterParameters,$with);
            $response['html'] = view($this->module . $this->view . 'index',
                compact('storeCode',
                     'filterParameters','storePayments')
            )->render();
            return response()->json($response);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getStorePaymentListForSupportAdmin(Request $request,$storeCode,$paymentFor)
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
                'page' => $request->page

            ];
           // dd($filterParameters['page']);
            $amountConditions=[
                'Greater Than >'=>'>',
                'Less Than <'=>'<' ,
                'Greater Than & Equal To >='=>'>=' ,
                'Less Than & Equal To <='=>'<=',
                'Equal To ='=>'=',
            ];
            //dd($filterParameters);
            $paymentsTypes = StoreMiscellaneousPayment::PAYMENT_TYPE;
            $paymentStatus = StoreMiscellaneousPayment::VERIFICATION_STATUSES;
            $with =['submittedBy','respondedBy','paymentDocuments','paymentMetaData'];
            $store = $this->storeService->findOrFailStoreByCode($storeCode);
            $storePayments = StoreMiscPaymentHelper::filterAllMiscPaymentsByStoreCodeAndPaymentType($filterParameters,$store->store_code,$paymentFor,
                StoreMiscellaneousPayment::RECORDS_PER_PAGE,$with);
            $response =[];
            $response['html'] = view($this->module . $this->view . 'payment-list',
                compact('storeCode',
                    'storePayments',
                    'store',
                    'filterParameters',
                    'paymentFor',
                    'paymentsTypes',
                    'amountConditions',
                    'paymentStatus'
                )
            )->render();
            return response()->json($response);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function showStoreMiscPaymentForSupportAdmin($miscPaymentCode)
    {
        try{
            $storePayment = $this->paymentService->findOrFailStoreMiscellaneousPaymentByCodeWithEager($miscPaymentCode);
            $store = $this->storeService->findOrFailStoreByCode($storePayment->store_code);
            $currentBalance = $this->storeBalance->getStoreActiveBalance($store);
            $storePayment = (new StoreMiscellaneousPaymentTransformer($storePayment))->transform();
            $response =[];
            $response['html'] = view($this->module . $this->view . 'payment-detail-modal',
                compact('storePayment','currentBalance'
                )
            )->render();
            return response()->json($response);

        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }




}




