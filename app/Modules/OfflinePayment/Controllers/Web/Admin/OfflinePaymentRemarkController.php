<?php

namespace App\Modules\OfflinePayment\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\OfflinePayment\Requests\OfflinePaymentRemarkCreateRequest;
use App\Modules\OfflinePayment\Services\OfflinePaymentRemarkService;
use App\Modules\OfflinePayment\Services\OfflinePaymentService;
use Exception;
use Illuminate\Support\Facades\DB;

class OfflinePaymentRemarkController extends BaseController
{
    public $title = 'Offline Payment Remarks';
    public $base_route = 'admin.stores.misc-payments.';
    public $sub_icon = 'file';
    public $module = 'OfflinePayment::';

    private $view='admin.offline-payments';

    private $offlinePaymentService;
    private $offlinePaymentRemarkService;

    public function __construct(
        OfflinePaymentService $offlinePaymentService,
        OfflinePaymentRemarkService $offlinePaymentRemarkService
    ){
        $this->offlinePaymentService  = $offlinePaymentService;
        $this->offlinePaymentRemarkService = $offlinePaymentRemarkService;
    }

    public function viewRemarksByOfflinePaymentCode($offlinePaymentCode){
        try{
            $with = ['offlinePaymentRemarks'];
            $offlinePayment = $this->offlinePaymentService->findOrFailOfflinePaymentByCodeWithEager($offlinePaymentCode,$with);
            $remarks = $offlinePayment->offlinePaymentRemarks;
            return view(Parent::loadViewData($this->module.$this->view.'.remarks.view-modal'),compact('offlinePayment','remarks'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function createRemarks($offlinePaymentCode){
        try{
            $offlinePayment = $this->offlinePaymentService->findOrFailOfflinePaymentByCodeWithEager($offlinePaymentCode);
            return view(Parent::loadViewData($this->module.$this->view.'.remarks.create-modal'),compact('offlinePayment'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function saveRemarks(OfflinePaymentRemarkCreateRequest $request,$offlinePaymentCode){
        try{
            DB::beginTransaction();
            $validated = $request->validated();
            $this->offlinePaymentService->findOrFailOfflinePaymentByCodeWithEager($offlinePaymentCode);
            $this->offlinePaymentRemarkService->saveRemarks($validated,$offlinePaymentCode);
            DB::commit();
            return $request->session()->flash('success','Remarks added for offline Payment ('.$offlinePaymentCode.')');
        }catch (Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}
