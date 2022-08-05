<?php

namespace App\Modules\Store\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Requests\Payment\MiscellaneousPaymentRemarkCreateRequest;
use App\Modules\Store\Services\Payment\MiscellaneousPaymentRemarksService;
use App\Modules\Store\Services\Payment\StorePaymentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MiscellaneousPaymentRemarkController extends BaseController
{

    public $title = 'Stores Miscellaneous Payments';
    public $base_route = 'admin.stores.misc-payments.';
    public $sub_icon = 'file';
    public $module = 'Store::';

    private $view='admin.store-payment.misc.';

    private $storePaymentService;
    private $miscellaneousPaymentRemarksService;

    public function __construct(
        StorePaymentService $storePaymentService,
        MiscellaneousPaymentRemarksService $miscellaneousPaymentRemarksService
    ){
        $this->storePaymentService  = $storePaymentService;
        $this->miscellaneousPaymentRemarksService = $miscellaneousPaymentRemarksService;
    }

    public function viewRemarksByMiscPaymentCode($storePaymentCode){
        try{
            $with = ['miscellaneousPaymentRemarks'];
            $storePayment = $this->storePaymentService->findOrFailStoreMiscellaneousPaymentByCodeWithEager($storePaymentCode,$with);
            $remarks = $storePayment->miscellaneousPaymentRemarks;
            return view(Parent::loadViewData($this->module.$this->view.'.remarks.view-modal'),compact('storePayment','remarks'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function createRemarks($storePaymentCode){
        try{
            $storePayment = $this->storePaymentService->findOrFailStoreMiscellaneousPaymentByCodeWithEager($storePaymentCode);

            return view(Parent::loadViewData($this->module.$this->view.'.remarks.create-modal'),compact('storePayment'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function saveRemarks(MiscellaneousPaymentRemarkCreateRequest $request,$storePaymentCode){
        try{
            DB::beginTransaction();
            $validated = $request->validated();
            $this->storePaymentService->findOrFailStoreMiscellaneousPaymentByCodeWithEager($storePaymentCode);
            $this->miscellaneousPaymentRemarksService->saveRemarks($validated,$storePaymentCode);
            DB::commit();
            return $request->session()->flash('success','Remarks add for Miscellaneous Payment ('.$storePaymentCode.')');
        }catch (Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}
