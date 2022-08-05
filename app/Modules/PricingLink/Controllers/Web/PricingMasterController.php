<?php


namespace App\Modules\PricingLink\Controllers\Web;

use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\PricingLink\Requests\CreatePricingMasterRequest;
use App\Modules\PricingLink\Requests\UpdatePricingMasterRequest;
use App\Modules\PricingLink\Services\PricingMasterService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class PricingMasterController extends BaseController
{
    public $title = 'Pricing Master';
    public $base_route = 'admin.pricing-master';
    public $sub_icon = 'file';
    public $module = 'PricingLink::';
    public $view = 'admin.pricing-master.';

    private $warehouseService,$pricingMasterService;

    public function __construct(WarehouseService $warehouseService,
                                PricingMasterService $pricingMasterService)
    {
        $this->middleware('permission:View Pricing Link Lists', ['only' => ['index']]);
        $this->middleware('permission:Create Pricing Link', ['only' => ['create', 'store']]);
        $this->middleware('permission:Update Pricing Link', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Change Pricing Link Status', ['only' => ['changePricingLinkStatus']]);

        $this->warehouseService = $warehouseService;
        $this->pricingMasterService = $pricingMasterService;
    }

    public function index(Request $request)
    {
        try{
            $pricingLinks = $this->pricingMasterService->getAllPricingLinks();

            return view(Parent::loadViewData($this->module . $this->view . 'index'),
                compact('pricingLinks')
            );

        }catch(Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }


    public function create()
    {
        $url = URL::to('/');
        $fullUrl = $url.'/product-pricing';
        $linkCode = Str::random(10);
        $fullLink = $fullUrl.'/'.$linkCode;
        $warehouses = $this->warehouseService->getAllWarehouses();
        return view(Parent::loadViewData($this->module . $this->view . 'create'),
            compact('warehouses',
                'fullLink','linkCode'
            )
        );
    }


    public function store(CreatePricingMasterRequest $request)
    {
        try{
            DB::beginTransaction();

            $validatedData = $request->validated();
            $pricingLink = $this->pricingMasterService->storePricingLink($validatedData);
            DB::commit();
            return redirect()->route('admin.pricing-master.index')->with('success',$this->title . ':  Created Successfully');

        }catch(Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }


    public function show()
    {


    }

    public function edit($pricingMasterCode)
    {
        try{
            $pricingLink = $this->pricingMasterService->findPricingLinkByCode($pricingMasterCode);
            $warehouses = $this->warehouseService->getAllWarehouses();
            return view(Parent::loadViewData($this->module . $this->view . 'edit'),
                compact('pricingLink','warehouses'));

        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }

    public function update(UpdatePricingMasterRequest $request,$pricingMasterCode)
    {
        try{
            DB::beginTransaction();
            $validatedData = $request->validated();
            $pricingLink = $this->pricingMasterService->updatePricingMaster($validatedData,$pricingMasterCode);
            DB::commit();
            return redirect()->route('admin.pricing-master.index')->with('success',$this->title.' '. $pricingMasterCode. ' :  updated Successfully');

        }catch(Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }

    public function changePricingLinkStatus($pricingMasterCode)
    {
        try{
            $pricingLink = $this->pricingMasterService->changePricingLinkStatus($pricingMasterCode);
            return redirect()->route('admin.pricing-master.index')->with('success','Status changed Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

}
