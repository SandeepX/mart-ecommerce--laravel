<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/2/2020
 * Time: 10:18 AM
 */

namespace App\Modules\Store\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Helpers\StoreOrderOfflinePaymentHelper;
use App\Modules\Store\Models\Payments\StoreOrderOfflinePayment;
use App\Modules\Store\Requests\Payment\StoreOrderOfflinePaymentRespondRequest;
use App\Modules\Store\Services\Payment\StoreOrderOfflinePaymentService;
use App\Modules\Store\Services\StoreService;

use App\Modules\Store\Transformers\StoreOrderOfflinePaymentTransformer;
use Exception;
use Illuminate\Http\Request;

class StoreOrderOfflinePaymentController extends BaseController
{

    public $title = 'Stores Offline Payments';
    public $base_route = 'admin.stores.offline-order-payments.';
    public $sub_icon = 'file';
    public $module = 'Store::';

    private $view='admin.store-payment.offline-order.';

    private $paymentService,$storeService;

    public function __construct(StoreOrderOfflinePaymentService $paymentService,StoreService $storeService)
    {

        $this->middleware('permission:View Store Order Offline Payment List', ['only' => ['getAllStoreOfflinePayments']]);
        $this->middleware('permission:Show Store Order Offline Payment', ['only' => ['showStoreOfflinePayment']]);
        $this->middleware('permission:Verify Store Order Offline Payment', ['only' => ['showStoreOfflinePayment','respondToStoreOfflinePayment']]);


        $this->paymentService = $paymentService;
        $this->storeService = $storeService;
    }

    public function getAllStoreOfflinePayments(Request $request){
        //dd(1);
        try{

            $filterParameters = [
                'store_name' =>$request->get('store_name'),
                'store_order_code' =>$request->get('store_order_code'),
                'payment_status' => $request->get('payment_status'),
                'payment_type' => $request->get('payment_type'),
                'payment_date_from' => $request->get('payment_date_from'),
                'payment_date_to' => $request->get('payment_date_to'),
            ];
            //$stores = $this->storeService->getAllStores();
            $paymentStatuses=StoreOrderOfflinePayment::PAYMENT_STATUSES;
            $paymentTypes = StoreOrderOfflinePayment::PAYMENT_TYPE;
            $with =[
                'store','submittedBy'
            ];

            //$storePayments = StoreOrderOfflinePaymentHelper::filterStoreOfflineOrderPaymentByParameters($filterParameters,$with);
            $storePayments = StoreOrderOfflinePaymentHelper::filterPaginatedStoreMiscPaymentByParameters($filterParameters,StoreOrderOfflinePayment::RECORDS_PER_PAGE,$with);
            return view(Parent::loadViewData($this->module.$this->view.'index'),
                compact('storePayments','paymentStatuses','paymentTypes','filterParameters'));

        }catch (Exception $e){
            return redirect()->route('admin.dashboard')->with('danger',$e->getMessage());
        }
    }

    public function showStoreOfflinePayment($offlinePaymentCode){
        
        try{
            $storePayment = $this->paymentService->findOrFailStoreOrderPaymentByCodeWithEager($offlinePaymentCode);

            $storePayment = (new StoreOrderOfflinePaymentTransformer($storePayment))->transform();

            return view(Parent::loadViewData($this->module.$this->view.'show'),
                compact('storePayment'));
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger',$exception->getMessage());
        }
    }

    public function respondToStoreOfflinePayment(StoreOrderOfflinePaymentRespondRequest $request,$offlinePaymentCode){
        try{
            $validated = $request->validated();

            $this->paymentService->respondToStoreOrderPaymentByAdmin($validated,$offlinePaymentCode);
            return redirect()->back()->with('success', $this->title .' responded successfully');
        }catch (Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }
}