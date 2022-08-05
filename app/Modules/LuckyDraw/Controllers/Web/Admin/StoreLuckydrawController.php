<?php

namespace App\Modules\LuckyDraw\Controllers\Web\Admin;

use App\Modules\LuckyDraw\Helpers\StoreLuckydrawFilter;
use App\Modules\LuckyDraw\Requests\StoreLuckydraw\StoreLuckydrawCreateRequest;
use App\Modules\LuckyDraw\Requests\StoreLuckydraw\StoreLuckydrawUpdateRequest;
use App\Modules\LuckyDraw\Services\StoreLuckydrawService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\LuckyDraw\Services\StoreLuckydrawWinnerService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use Exception;

class StoreLuckydrawController extends BaseController
{

    public $title = 'Store Lucky Draw';
    public $base_route = 'admin.store-lucky-draws';
    public $sub_icon = 'file';
    public $module = 'LuckyDraw::';


    private $view='admin.prizes.';

    private $storeLuckydrawService,$storeLuckydrawWinnerService;



    public function __construct(
        StoreLuckydrawService $storeLuckydrawService,
        StoreLuckydrawWinnerService $storeLuckydrawWinnerService

    )
    {
//        $this->middleware('permission:View Store List', ['only' => ['index','getUnapprovedStores']]);
//        $this->middleware('permission:Create Store', ['only' => ['create','store']]);
//        $this->middleware('permission:Show Store', ['only' => ['show']]);
//        $this->middleware('permission:Update Store', ['only' => ['edit','update']]);
//        $this->middleware('permission:Delete Store', ['only' => ['destroy']]);
//        $this->middleware('permission:Update Store Status', ['only' => ['updateStatus','changeStatus']]);
//        $this->middleware('permission:Update Store Purchase Power',['only'=>['togglePurchasePowerStatus']]);

        $this->storeLuckydrawService = $storeLuckydrawService;
        $this->storeLuckydrawWinnerService = $storeLuckydrawWinnerService;


    }



    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        try{

            $luckyDrawName = $request->get('luckydraw_name');
            $luckyDrawCode = $request->get('store_luckydraw_code');
            $status = $request->get('status');
            $type = $request->get('type');


            $filterParameters = [
                'luckydraw_name' => $luckyDrawName,
                'store_luckydraw_code' => $luckyDrawCode,
                'status' => $status,
                'type' => $type,
            ];

            //dd($filterParameters);
            $storeLuckydraws = StoreLuckydrawFilter::filterPaginatedPrizes($filterParameters,10);

            return view(Parent::loadViewData($this->module.$this->view.'index'),
                compact('storeLuckydraws', 'filterParameters'));
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

        return view(Parent::loadViewData($this->module.$this->view.'create'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(StoreLuckydrawCreateRequest $storeLuckydrawCreateRequest)
    {
        try{
            $validatedData = $storeLuckydrawCreateRequest->validated();
            $storeLuckydraw = $this->storeLuckydrawService->storePrize($validatedData);

        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

        return redirect()->back()->with('success', $this->title . ': '. $storeLuckydraw->luckydraw_name .' created Successfully')->withInput();
    }

    /**
     * Show the specified resource.
     * @return Response
     */
//    public function show($storeCode)
//    {
//        $store = $this->storeService->findOrFailStoreByCode($storeCode);
//        $store = (new StoreDetailTransformer($store))->transform();
//
//        return view(Parent::loadViewData($this->module.$this->view.'show'),compact('store'));
//
//    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($storeLuckydrawCode)
    {
        try{
            $storeLuckydraw = $this->storeLuckydrawService->findOrFailStoreLuckydrawByCode($storeLuckydrawCode);

            if($storeLuckydraw->status === 'closed')
            {
                throw new Exception('Closed Store LuckyDraw Can not be edited !');
            }

            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('storeLuckydraw'));

        }catch (\Exception $ex){
            return redirect()->back()->with('danger',$ex->getMessage());
        }

    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(StoreLuckydrawUpdateRequest $storeLuckydrawUpdateRequest, $storeLuckydrawCode)
    {
        $validatedData = $storeLuckydrawUpdateRequest->validated();
        try{
            $storeLuckydraw = $this->storeLuckydrawService->updatePrize($validatedData, $storeLuckydrawCode);

        }catch(\Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
        return redirect()->back()->with('success', $this->title . ': '. $storeLuckydraw->luckydraw_name .' Updated Successfully')->withInput();

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($storeLuckydrawCode)
    {
        try{

            $storeLuckydraw = $this->storeLuckydrawService->deletePrize($storeLuckydrawCode);

            return redirect()->back()->with('success', $this->title . ': '. $storeLuckydraw->luckydraw_name .' Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function toggleStatus($SLCode,$status){
        try{

            $updateStatus = $this->storeLuckydrawService->changeStoreLuckydrawStatus($SLCode,$status);
            return redirect()->back()->with('success','Store Lucky Draw :'.$updateStatus->luckydraw_name.' status changed successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function changeStatus($SLCode,$status){
        try{
            $updateStatus = $this->storeLuckydrawService->changeStoreLuckyDrawActiveStatus($SLCode,$status);
            return redirect()->back()->with('success','Store Lucky Draw :'.$updateStatus->luckydraw_name.' status changed successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function preLoadStorePage($storeLuckydrawCode)
    {
        try{
            $storeLuckydraw = $this->storeLuckydrawService->findOrFailStoreLuckydrawByCode($storeLuckydrawCode);
            return view(Parent::loadViewData($this->module.$this->view.'pre-load-store-page'),compact('storeLuckydraw'));
        }
        catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }
    public function preLoadStoreLists($storeLuckydrawCode)
    {
        try{
            $currentDate = date('Y-M-d h:i:s',strtotime(Carbon::now()));
            $storeLuckydraw = $this->storeLuckydrawService->findOrFailStoreLuckydrawByCode($storeLuckydrawCode);
            $cacheData = Cache::get($storeLuckydraw->store_luckydraw_code);
            if(isset($cacheData)){
                Cache::forget($storeLuckydraw->store_luckydraw_code);
            }
            $notWinnerStores = StoreLuckydrawFilter::getNotWinnerStores($storeLuckydraw);
            //dd($notWinnerStores);
             Cache::put($storeLuckydraw->store_luckydraw_code,collect($notWinnerStores));
             Cache::put('last_pre_loaded_'.$storeLuckydraw->store_luckydraw_code,$currentDate);
             $cachedStores = Cache::get($storeLuckydraw->store_luckydraw_code);
             $totalStores = $cachedStores->count();
             $cachedStores = StoreLuckydrawFilter::paginate($cachedStores,10);
             $lastPreLoadedTime = Cache::get('last_pre_loaded_'.$storeLuckydraw->store_luckydraw_code);
            return view(Parent::loadViewData($this->module.$this->view.'pre-load-store-page'),
                compact('storeLuckydraw','cachedStores','lastPreLoadedTime','totalStores'));
        }
        catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }
//    public function openLuckydraw($storeLuckydrawCode)
//    {
//        try{
//            $storeLuckydraw = $this->storeLuckydrawService->findOrFailStoreLuckydrawByCode($storeLuckydrawCode);
//            if($storeLuckydraw->status != 'pending' || $storeLuckydraw->is_active === 0)
//            {
//                throw new Exception('Only Pending and Active Luckydraw Can be opened !');
//            }
//            $openLuckydraw = $this->storeLuckydrawWinnerService->createStoreLuckydrawWinner($storeLuckydraw);
//            return redirect()->back()->with('success','Store Lucky Draw :'.$openLuckydraw->luckydraw_name.' opened successfully');
//        }catch(\Exception $exception){
//            return redirect()->back()->with('danger', $exception->getMessage());
//        }
//    }
//
//    public function reSelectWinner($storeLuckydrawCode)
//    {
//        try{
//            $storeLuckydraw = $this->storeLuckydrawService->findOrFailStoreLuckydrawByCode($storeLuckydrawCode);
//            if($storeLuckydraw->status != 'open' || $storeLuckydraw->is_active === 0)
//            {
//                throw new Exception('Only Open and Active Luckydraw Can ReSelect Winner !');
//            }
//            $openLuckydraw = $this->storeLuckydrawWinnerService->createStoreLuckydrawWinner($storeLuckydraw);
//            return redirect()->back()->with('success','Store Lucky Draw :'.$openLuckydraw->luckydraw_name.' ReSelected Winner successfully');
//        }catch(\Exception $exception){
//            return redirect()->back()->with('danger', $exception->getMessage());
//        }
//    }
}

