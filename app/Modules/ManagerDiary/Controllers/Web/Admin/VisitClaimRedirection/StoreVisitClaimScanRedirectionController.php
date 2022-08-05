<?php

namespace App\Modules\ManagerDiary\Controllers\Web\Admin\VisitClaimRedirection;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\ManagerDiary\Models\VisitClaim\StoreVisitClaimScanRedirection;
use App\Modules\ManagerDiary\Requests\VisitClaimRedirection\CreateStoreVisitClaimScanRedirectionRequest;
use App\Modules\ManagerDiary\Requests\VisitClaimRedirection\UpdateStoreVisitClaimScanRedirectionRequest;
use App\Modules\ManagerDiary\Services\VisitClaimRedirection\StoreVisitClaimScanRedirectionService;
use Exception;

class StoreVisitClaimScanRedirectionController extends BaseController
{
    public $title = 'Store Visit Claim Scan Redirection';
    public $base_route = 'admin.visit-claim-scan-redirection';
    public $sub_icon = 'home';
    public $module = 'ManagerDiary::';
    private $view = 'admin.visit-claim-redirections.';

    private $storeVisitClaimScanRedirectionService;
    public function __construct(StoreVisitClaimScanRedirectionService $storeVisitClaimScanRedirectionService)
    {
        $this->storeVisitClaimScanRedirectionService = $storeVisitClaimScanRedirectionService;
    }

    public function index(){
        try{
            $storeVisitClaimRedirections = $this->storeVisitClaimScanRedirectionService->getAllPaginatedStoreVisitClaimRedirection(10);
            return view(parent::loadViewData($this->module.$this->view.'index'),compact('storeVisitClaimRedirections'));
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger',$exception->getMessage());
        }
    }

    public function create(){
        try{
            $appPages = StoreVisitClaimScanRedirection::APP_PAGE;
            return view(parent::loadViewData($this->module.$this->view.'create'),compact('appPages'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function store(CreateStoreVisitClaimScanRedirectionRequest $request){
        try{
            $validatedData = $request->validated();
            $this->storeVisitClaimScanRedirectionService->storeVisitClaimScanRedirection($validatedData);
            return redirect()->back()->with('success', $this->title .' Created Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function edit($storeVisitClaimScanRedirectionCode){
        try{

            $appPages = StoreVisitClaimScanRedirection::APP_PAGE;
            $storeVisitClaimScanRedirection  = $this->storeVisitClaimScanRedirectionService
                                                    ->findOrFailStoreVisitScanRedirectionByCode($storeVisitClaimScanRedirectionCode);

            return view(parent::loadViewData($this->module.$this->view.'edit'),compact('storeVisitClaimScanRedirection','appPages'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function update(UpdateStoreVisitClaimScanRedirectionRequest $request,$storeVisitClaimScanRedirectionCode){
        try{
            $validatedData = $request->validated();
            $visitClaimScanRedirect = $this->storeVisitClaimScanRedirectionService
                                            ->updateVisitClaimScanRedirection($storeVisitClaimScanRedirectionCode,$validatedData);
            return redirect()->back()->with('success', $this->title .' updated Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function destroy($storeVisitClaimScanRedirectionCode){
        try{
            $this->storeVisitClaimScanRedirectionService->deleteScanRedirection($storeVisitClaimScanRedirectionCode);
            return redirect()->back()->with('success', $this->title.' Bank Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

}
