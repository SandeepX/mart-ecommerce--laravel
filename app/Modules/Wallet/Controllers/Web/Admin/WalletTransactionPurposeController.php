<?php

namespace App\Modules\Wallet\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Types\Repositories\UserTypeRepository;
use App\Modules\Types\Services\UserTypeService;
use App\Modules\Wallet\Models\WalletTransactionPurpose;
use App\Modules\Wallet\Requests\WalletTransactionPurposeCreateRequest;
use App\Modules\Wallet\Requests\WalletTransactionPurposeUpdateRequest;
use App\Modules\Wallet\Services\WalletTransactionPurposeService;
use App\Modules\Wallet\Services\WalletTransactionService;
use Exception;

class WalletTransactionPurposeController extends BaseController
{
    public $title = 'Wallet Transaction Purpose';
    public $base_route = 'admin.wallets.transactions-purpose';
    public $sub_icon = 'file';
    public $module = 'Wallet::';

    private $view = 'wallets.transactions-purpose.';

    private $walletTransactionPurposeService;
    private $walletTransactionService;
    private $userTypeService;

    public function __construct(
        WalletTransactionPurposeService $walletTransactionPurposeService,
        WalletTransactionService $walletTransactionService,
        UserTypeService $userTypeService
    )
    {
        $this->middleware('permission:View Wallet Transaction Purpose Lists', ['only' => ['index']]);
        $this->middleware('permission:Create Wallet Transaction Purpose', ['only' => ['create', 'store']]);
        $this->middleware('permission:Update Wallet Transaction Purpose', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Delete Wallet Transaction Purpose', ['only' => ['destroy']]);
        $this->middleware('permission:Change Wallet Transaction Purpose Status', ['only' => ['toggleStatus']]);

        $this->walletTransactionPurposeService = $walletTransactionPurposeService;
        $this->walletTransactionService = $walletTransactionService;
        $this->userTypeService = $userTypeService;
    }

    public function index()
    {

        try{
            $walletTransactionsPurpose = $this->walletTransactionPurposeService->getAllPaginatedWalletTransactionPurpose(WalletTransactionPurpose::PAGINATED_BY);
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('walletTransactionsPurpose'));
        }catch (Exception $e){
            return redirect()->route('admin.dashboard')->with('danger',$e->getMessage());
        }
    }

    public function create()
    {
        try{
            $userTypes = $this->userTypeService->getAllActiveUserTypes();
            return view(Parent::loadViewData($this->module.$this->view.'create'),compact('userTypes'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }


    public function store(WalletTransactionPurposeCreateRequest $request)
    {
        try{
            $validated = $request->validated();
            $walletTransactionPurpose =  $this->walletTransactionPurposeService->storeWalletTransactionPurpose($validated);
            return redirect()->back()->with('success', $this->title . ': '. $walletTransactionPurpose->purpose .' Created Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }


    public function show()
    {

    }


    public function edit($walletTransactionPurposeCode)
    {
        try{

            $walletTransactionPurpose = $this->walletTransactionPurposeService->findorFailByTransactionPurposeCode($walletTransactionPurposeCode);

            if($walletTransactionPurpose->is_active){
                throw new Exception('Cannot edit because wallet transaction purpose is active');
            }

            if($walletTransactionPurpose->close_for_modification){
                throw new Exception('Cannot edit wallet transaction purpose. It is Closed For Modification');
            }

            $checkWalletTransactionPurposeUses = $this->walletTransactionService->checkUsesOfWalletTransactionPurposeInTransactions(
                $walletTransactionPurpose->wallet_transaction_purpose_code
            );

            if($checkWalletTransactionPurposeUses){
                throw new Exception('Cannot edit wallet transaction purpose . It is used in Wallet Transactions');
            }

            $userTypes = $this->userTypeService->getAllActiveUserTypes();
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('userTypes','walletTransactionPurpose'));

        }catch(Exception $exception){
            return redirect()->route($this->base_route.'.index')->with('danger',$exception->getMessage());
        }

    }


    public function update(WalletTransactionPurposeUpdateRequest $request,$walletTransactionPurposeCode)
    {
        try{
            $validated = $request->validated();
            $walletTransactionPurpose =  $this->walletTransactionPurposeService->updateWalletTransactionPurpose($walletTransactionPurposeCode,$validated);
            return redirect()->route($this->base_route.'.index')->with('success', $this->title . ': '. $walletTransactionPurpose->purpose .' Updated Successfully');
        }catch (Exception $exception){
            return redirect()->route($this->base_route.'.index')->with('danger',$exception->getMessage());
        }

    }


    public function destroy($walletTransactionPurposeCode)
    {
        try{
            $this->walletTransactionPurposeService->deleteWalletTransactionPurpose($walletTransactionPurposeCode);
            return redirect()->back()->with('success', 'Wallet Transaction Purpose Deleted Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

    public function toggleStatus($walletTransactionPurposeCode){

        try{
            $this->walletTransactionPurposeService->toggleStatus($walletTransactionPurposeCode);
            return redirect()->back()->with('success', 'Wallet Transaction Purpose status changed  Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }



}
