<?php

namespace App\Modules\LuckyDraw\Controllers\Web\Admin;

use App\Modules\LuckyDraw\Helpers\StoreLuckydrawFilter;
use App\Modules\LuckyDraw\Requests\PrefixWinner\PrefixWinnerCreateRequest;
use App\Modules\LuckyDraw\Requests\PrefixWinner\PrefixWinnerUpdateRequest;
use App\Modules\LuckyDraw\Services\PrefixWinnerService;
use App\Modules\LuckyDraw\Services\StoreLuckydrawService;
use App\Modules\Application\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Exception;

class PrefixWinnerController extends BaseController
{

    public $title = 'Prefix Winner';
    public $base_route = 'admin.prefix-winners';
    public $sub_icon = 'file';
    public $module = 'LuckyDraw::';


    private $view='admin.prefix-winners.';

    private $prefixWinnerService, $storeLuckydrawService;



    public function __construct(
        PrefixWinnerService $prefixWinnerService,
        StoreLuckydrawService $storeLuckydrawService

    )
    {

        $this->prefixWinnerService = $prefixWinnerService;
        $this->storeLuckydrawService = $storeLuckydrawService;

    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        try{

            $prefixWinners = $this->prefixWinnerService->getAllPrefixWinners();

            return view(Parent::loadViewData($this->module.$this->view.'index'),
                compact('prefixWinners'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
      //
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(PrefixWinnerCreateRequest $prefixWinnerCreateRequest)
    {
        try{
            $validatedData = $prefixWinnerCreateRequest->validated();
            $prefixWinner = $this->prefixWinnerService->storePrefixWinner($validatedData);

        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

        return redirect()->back()->with('success', 'Prefix winner created Successfully')->withInput();
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($storeLuckydrawCode)
    {
        $storeLuckydraw = $this->storeLuckydrawService->findOrFailStoreLuckydrawByCode($storeLuckydrawCode);

        return view(Parent::loadViewData($this->module.$this->view.'show'),compact('storeLuckydraw'));

    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($prefixWinnerCode,$storeLuckydrawCode)
    {
        try{

            $prefixWinner = $this->prefixWinnerService->findOrFailPrefixWinnerByCode($prefixWinnerCode);
            $storeLuckydrawDetail = $this->storeLuckydrawService->findOrFailStoreLuckydrawByCode($storeLuckydrawCode);
            if($storeLuckydrawDetail->status === 'closed')
            {
                throw new Exception('Closed Store LuckyDraw Can not be edited !');
            }
            $stores = StoreLuckydrawFilter::getNotWinnerStores($storeLuckydrawDetail);

            $stores = collect($stores);

            $eligibleStores = $stores->where('eligibility',1);

            $notEligibleStores = $stores->where('eligibility',0);


            return view(Parent::loadViewData($this->module.$this->view.'edit'),
                compact('prefixWinner','eligibleStores','notEligibleStores','storeLuckydrawCode'));

        }catch (\Exception $ex){
            return redirect()->back()->with('danger',$ex->getMessage());
        }

    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(PrefixWinnerUpdateRequest $prefixWinnerUpdateRequest, $prefixWinnerCode)
    {
        $validatedData = $prefixWinnerUpdateRequest->validated();
        try{
            $prefixWinner = $this->prefixWinnerService->updatePrefixWinner($validatedData, $prefixWinnerCode);

        }catch(\Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
        return redirect()->back()->with('success','Prefix Winner Updated Successfully')->withInput();

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($prefixWinnerCode)
    {
        try{
            $prefixWinner = $this->prefixWinnerService->deletePrefixWinner($prefixWinnerCode);

            return redirect()->back()->with('success', 'Prefix Winner Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }


    public function getStoresForPrefixWinner(Request $request,$storeLuckydrawCode){
        try{

            $storeLuckydrawDetail =$this->storeLuckydrawService->findOrFailStoreLuckydrawByCode($storeLuckydrawCode);
            if($storeLuckydrawDetail->is_active === 0)
            {
                throw new Exception('Can not prefix winner because the luckydraw is not active');
            }
            $stores = StoreLuckydrawFilter::getNotWinnerStores($storeLuckydrawDetail);

            $stores = collect($stores);
            $eligibleStores = $stores->where('eligibility',1);
            $notEligibleStores = $stores->where('eligibility',0);
            if ($request->ajax()) {
                return view('LuckyDraw::admin.prefix-winners.prefix-winner-form',
                    compact('storeLuckydrawDetail','eligibleStores','notEligibleStores'))->render();
            }
            return $stores;
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function changePrefixWinnerDisplayOrder(Request $request,$storeLuckydrawCode){
        try{
            $sortOrdersToChange = $request->sort_order;
            $changeOrder = $this->prefixWinnerService->changePackageDisplayOrder($storeLuckydrawCode,$sortOrdersToChange);
            return sendSuccessResponse('Display Order Updated');
        }catch(\Exception $exception){
            return sendErrorResponse('Sorry ! Could not update display order');
        }
    }
}

