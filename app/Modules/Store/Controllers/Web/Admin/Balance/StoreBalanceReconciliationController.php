<?php
/**
 * Created by PhpStorm.
 * User: Sandeep
 * Date: 1/3/2021
 * Time: 12:52 PM
 */

namespace App\Modules\Store\Controllers\Web\Admin\Balance;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Exports\StoreBalanceReconciliationExport;
use App\Modules\Store\Imports\StoreBalanceReconciliationImport;
use App\Modules\Store\Requests\BalanceReconciliation\StoreBalanceReconciliationStoreRequest;
use App\Modules\Store\Requests\BalanceReconciliation\StoreBalanceReconciliationUpdateRequest;
use App\Modules\Store\Services\StoreBalanceReconciliation\StoreBalanceReconciliationService;
use App\Modules\Store\Requests\BalanceReconciliation\StoreBalanceReconciliationImportRequest;
use App\Modules\Store\Helpers\BalanceReconciliationHelper;


use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Exception;
use DB;


class StoreBalanceReconciliationController extends BaseController
{

    public $title = 'Stores Balance Reconciliation';
    public $base_route = 'admin.balance';
    public $sub_icon = 'file';
    public $module = 'Store::';
    private $view = 'BalanceManagement.balance-reconciliation';

    private $reconciliationService;

    public function __construct(StoreBalanceReconciliationService $reconciliationService)
    {
        $this->middleware('permission:View Store Balance Reconciliation List', ['only' => ['index','getImportPage','importReconciliation']]);
        $this->middleware('permission:Create Store Balance Reconciliation', ['only' => ['create','store']]);
        $this->middleware('permission:Show Store Balance Reconciliation', ['only' => ['show']]);
        $this->middleware('permission:Update Store Balance Reconciliation', ['only' => ['edit','update']]);
        $this->middleware('permission:Change Store Balance Reconciliation Status', ['only' => ['changeStatus']]);


        $this->reconciliationService  = $reconciliationService;

    }

    public function index(Request $request)
    {

        $filterParameters = [
            'transaction_type' =>$request->get('transaction_type'),
            'payment_method' => $request->get('payment_method'),
            'payment_method_name'=>$request->get('payment_body_code'),
            'transaction_no' => $request->get('transaction_no'),
            'transaction_from'=> $request->get('transaction_from'),
            'transaction_to'=> $request->get('transaction_to'),
            'created_from'=> $request->get('created_from'),
            'created_to'=> $request->get('created_to'),
            'transaction_amount'=>$request->get('transaction_amount'),
            'transacted_by' =>$request->get('transacted_by'),
            'amount_condition'=>$request->get('amount_condition'),
            'status' =>$request->get('status'),
            'description' =>$request->get('description'),
            'balance_reconciliation_code' => $request->get('balance_reconciliation_code')
        ];


        $amountConditions=[
            'Greater Than >'=>'>',
            'Less Than <'=>'<' ,
            'Greater Than & Equal To >='=>'>=' ,
            'Less Than & Equal To <='=>'<=',
            'Equal To ='=>'=',
        ];
        //dd($filterParameters);

        $balanceReconciliationDetail = BalanceReconciliationHelper::getAllBalanceReconciliationFilter($filterParameters);

        return view(Parent::loadViewData($this->module.$this->view.'.index'),compact('balanceReconciliationDetail','filterParameters','amountConditions'));
    }

    public function create()
    {
      return view(Parent::loadViewData($this->module.$this->view.'.create'));
    }

    public function store(StoreBalanceReconciliationStoreRequest $request)
    {
        try{
            $validated = $request->validated();
            $validated['description'] = removeSpecialChar($validated['description']);
            $this->reconciliationService->createBalanceReconciliation($validated);

            return redirect()->back()->with('success', $this->title .' created successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function show($balanceReconciliationCode)
    {
        try{
            $reconciliationDetail= $this->reconciliationService->findorfailBalanceReconciliationByCode($balanceReconciliationCode);

            return view(Parent::loadViewData($this->module.$this->view.'.show'),compact('reconciliationDetail'));

        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function edit($balanceReconciliationCode)
    {
       try{
           $reconciliationDetail= $this->reconciliationService->findorfailBalanceReconciliationByCode($balanceReconciliationCode);

           return view(Parent::loadViewData($this->module.$this->view.'.edit'),compact('reconciliationDetail'));
       }catch(Exception $exception){
           return redirect()->back()->with('danger', $exception->getMessage())->withInput();
       }
    }

    public function update(StoreBalanceReconciliationUpdateRequest $request, $balanceReconciliationCode)
    {
        try{
            $validated = $request->validated();
            $validated['description'] = removeSpecialChar($validated['description']);
            $this->reconciliationService->updateStoreReconciliationDetailByCode($validated,$balanceReconciliationCode);
            return redirect()->route('admin.balance.reconciliation')->with('success', $this->title .' updated successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage())->withInput();
        }
    }

    public function getPaymentBody(Request $request)
    {
        try{
           return $this->reconciliationService->getPaymentBodyCode($request->payment_method);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getPaymentBodyForUpdate(Request $request)
    {
        try{
            return $this->reconciliationService->getPaymentBodyForUpdate($request);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }


//    public function destroy($balanceReconciliationCode)
//    {
//        try{
//            $this->reconciliationService->deleteBalanceReconciliationByCode($balanceReconciliationCode);
//            return redirect()->back()->with('success',$this->title .' deleted Successfully');
//
//        }catch(Exception $exception){
//            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
//        }
//    }


     public function changeStatus(Request $request)
     {
         try{
             $balance_reconciliation_code = $request->reconciliationCode;
             $remarks = isset($request->remarks) ? $request->remarks : null;
             $this->reconciliationService->changeStatusFromUnusedToUsed($balance_reconciliation_code,$remarks);
             return $request->session()->flash('success',$this->title .' status changed successfully');
        }catch(Exception $exception){
             return $request->session()->flash('danger', $exception->getMessage());

        }
     }

     public function getImportPage() {
        return view(Parent::loadViewData($this->module.$this->view.'.import'));
     }

     public function importReconciliation(StoreBalanceReconciliationImportRequest $request)
     {
         try {
             $file = $request->file('import_file');
             $import = new StoreBalanceReconciliationImport();
             $import->import($file);

             if ($import->failures()->isNotEmpty()) {
                 $fileName = time().'balance_reconciliation.xlsx';
                 Excel::store(new StoreBalanceReconciliationExport($import->failures()), 'balance_reconciliation/generated/'.$fileName, 'public');
                 return redirect()->back()->with(['warn' => 'Please download the list of repeated transaction number and try again later', 'fileName' => $fileName]);
             }

             return redirect()->back()->with('success', 'File imported successfully!');
         } catch (Exception $exception) {
             return redirect()->back()->with('danger', $exception->getMessage())->withInput();
         }
     }



}





