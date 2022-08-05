<?php

namespace App\Modules\Bank\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Bank\Requests\BankCreateRequest;
use App\Modules\Bank\Requests\BankUpdateRequest;
use App\Modules\Bank\Services\BankService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BankController extends BaseController
{

    public $title = 'Bank';
    public $base_route = 'admin.banks';
    public $sub_icon = 'file';
    public $module = 'Bank::';
    public $view = 'admin.';

    private $bankService;

    public function __construct(BankService $bankService)
    {
        $this->middleware('permission:View Bank List', ['only' => ['index']]);
        $this->middleware('permission:Create Bank', ['only' => ['create','store']]);
        $this->middleware('permission:Show Bank', ['only' => ['show']]);
        $this->middleware('permission:Update Bank', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Bank', ['only' => ['destroy']]);

        $this->bankService = $bankService;

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $banks = $this->bankService->getAllBanks();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view(Parent::loadViewData($this->module.$this->view.'create'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(BankCreateRequest $request)
    {
        $validated = $request->validated();
        try{
            $bank =  $this->bankService->storeBank($validated);
            return redirect()->back()->with('success', $this->title . ': '. $bank->bank_name .' Created Successfully');
        }catch(\Exception $exception){
             return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
//    public function show($bankSlug)
//    {
//        return view('Bank::show');
//    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($bankCode)
    {
        try{
            $bank = $this->bankService->findOrFailBankByCode($bankCode);
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('bank'));
        }catch (\Exception $ex){
            return redirect()->back()->with('danger',$ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(BankUpdateRequest $request, $bank)
    {
        $validated = $request->validated();
        try{
           $bank = $this->bankService->updateBank($validated, $bank);
           return redirect()->back()->with('success', $this->title . ': '. $bank->bank_name .' Updated Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($bankSlug)
    {
        try{
            $bank = $this->bankService->deleteBank($bankSlug);
            return redirect()->back()->with('success', $this->title . ': '. $bank->bank_name .' Bank Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
