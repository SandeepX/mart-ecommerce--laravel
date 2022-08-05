<?php


namespace App\Modules\PricingLink\Controllers\Web;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Location\Services\LocationHierarchyService;
use App\Modules\PricingLink\Exports\PricingLinkLeadExport;
use App\Modules\PricingLink\Services\LeadService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadController extends BaseController
{
    public $title = 'Pricing Link Lead';
    public $base_route = 'admin.pricing-link-lead';
    public $sub_icon = 'file';
    public $module = 'PricingLink::';
    public $view = 'admin.pricing-link-lead.';

    private $leadService,$locationHierarchyService;

    public function __construct(LeadService $leadService,
                                LocationHierarchyService $locationHierarchyService)
    {

        $this->leadService = $leadService;
        $this->locationHierarchyService = $locationHierarchyService;
    }

    public function index(Request $request)
    {
        try{
            $is_verified = $request->get('is_verified');
            $joinedDateFrom = $request->get('joined_date_from');
            $joinedDateTo = $request->get('joined_date_to');
            $province = $request->get('province');
            $district = $request->get('district');
            $municipality = $request->get('municipality');
            $ward = $request->get('ward');

            $filterParameters = [
                'is_verified' => $is_verified,
                'joined_date_from' => $joinedDateFrom,
                'joined_date_to' => $joinedDateTo,
                'province' => $province,
                'district' => $district,
                'municipality' => $municipality,
                'ward' => $ward,
            ];
            $pricingLinkLeads = $this->leadService->getAllPricingLinkLeads($filterParameters);
            $provinces = $this->locationHierarchyService->getAllLocationsByType('province');
            return view(Parent::loadViewData($this->module . $this->view . 'index'),
                compact('pricingLinkLeads','provinces','filterParameters')
            );

        }catch(Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }
    public function exportExcellPricingLinkLead(Request $request)
    {
        try{
            $is_verified = $request->get('is_verified');
            $joinedDateFrom = $request->get('joined_date_from');
            $joinedDateTo = $request->get('joined_date_to');
            $province = $request->get('province');
            $district = $request->get('district');
            $municipality = $request->get('municipality');
            $ward = $request->get('ward');

            $filterParameters = [
                'is_verified' => $is_verified,
                'joined_date_from' => $joinedDateFrom,
                'joined_date_to' => $joinedDateTo,
                'province' => $province,
                'district' => $district,
                'municipality' => $municipality,
                'ward' => $ward,
            ];

            $pricingLinkLeads = $this->leadService->getAllPricingLinkLeads($filterParameters);
            if ($request->ajax()) {

                return (new PricingLinkLeadExport($pricingLinkLeads,$this->module, $this->view));
            }
            return (new PricingLinkLeadExport($pricingLinkLeads,$this->module, $this->view));
        } catch (Exception $e) {
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

}
