<?php

namespace App\Modules\LuckyDraw\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\LuckyDraw\Helpers\StoreLuckydrawFilter;
use App\Modules\LuckyDraw\Resources\StoreLuckydrawDetailResource;
use App\Modules\LuckyDraw\Resources\StoreLuckydrawDetailWithWinnerResource;
use App\Modules\LuckyDraw\Resources\StoreLuckydrawResource;
use App\Modules\LuckyDraw\Resources\StoreResource;
use App\Modules\LuckyDraw\Services\StoreLuckydrawService;
use App\Modules\LuckyDraw\Services\StoreLuckydrawWinnerService;
use App\Modules\Store\Services\StoreService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use function React\Promise\map;

class StoreLuckydrawController extends Controller
{
    private $storeLuckydrawService,$storeLuckydrawWinnerService,$storeService;

    public function __construct(
        StoreLuckydrawService $storeLuckydrawService,
        StoreLuckydrawWinnerService $storeLuckydrawWinnerService,
        StoreService $storeService
    )
    {
        $this->storeLuckydrawService = $storeLuckydrawService;
        $this->storeLuckydrawWinnerService = $storeLuckydrawWinnerService;
        $this->storeService = $storeService;
    }

    public function getStoreLuckydraws(Request $request,$status)
    {
        try {
            $paginatedBy = $request->get('records_per_page');
            $perPage = isset($paginatedBy) ? $paginatedBy : 10;
            $storeLuckydraws = $this->storeLuckydrawService->getAllPaginatedLuckydraws($status,$perPage);
            return StoreLuckydrawDetailResource::collection($storeLuckydraws);

        }
        catch (Exception $exception)
        {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function storeLuckydrawDetail($storeLuckydrawCode){
        try{
           $storeLuckydraw = $this->storeLuckydrawService->findOrFailStoreLuckydrawByCode($storeLuckydrawCode);
           $storeLuckydraw = new StoreLuckydrawDetailWithWinnerResource($storeLuckydraw);
            return sendSuccessResponse('Data Found',$storeLuckydraw);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function openLuckyDraw($storeLuckydrawCode,Request $request)
    {

        try{
            $storeLuckydraw = $this->storeLuckydrawService->findOrFailStoreLuckydrawByCode($storeLuckydrawCode);
            if($storeLuckydraw->status === 'closed' || $storeLuckydraw->is_active === 0)
            {
                throw new Exception('Only Pending, Open and Active Luckydraw Can be opened !',403);
            }
            if($storeLuckydraw->status === 'open')
            {
                $cache = Cache::get($storeLuckydraw->store_luckydraw_code);
                $storeLuckydrawWinnersCode= $storeLuckydraw->storeLuckydrawWinners->pluck('store_code')->toArray();
                if(count($storeLuckydrawWinnersCode) > 0){

                    $fromCache = $cache->whereNotIn('store_code',$storeLuckydrawWinnersCode);
                    Cache::forget($storeLuckydraw->store_luckydraw_code);
                    Cache::put($storeLuckydraw->store_luckydraw_code,$fromCache);
                }


            }
            if($storeLuckydraw->status === 'pending')
            {
                $storeLuckydraw = $this->storeLuckydrawService->changeStoreLuckydrawStatus($storeLuckydraw,'open');
            }

            $cachedNotWinnerStores = Cache::get($storeLuckydraw->store_luckydraw_code,[]);
            if(count($cachedNotWinnerStores) > 0){

                $notWinnerStores = $cachedNotWinnerStores;
            }else{
                $notWinnerStores = StoreLuckydrawFilter::getNotWinnerStores($storeLuckydraw);
                $notWinnerStores = collect($notWinnerStores);
                Cache::put($storeLuckydraw->store_luckydraw_code,$notWinnerStores);
            }

           $storeLuckydraw = new StoreLuckydrawDetailResource($storeLuckydraw);

            $chunkedStores = array_chunk($notWinnerStores->toArray(),100,false);
            return sendSuccessResponse('Data Found',[
                'store_lucky_draw'=>$storeLuckydraw,
                'stores_list'=>$chunkedStores,
            ]);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }


    public function selectLuckyDrawWinner($storeLuckydrawCode)
    {
        try{
            $storeLuckydraw = $this->storeLuckydrawService->findOrFailStoreLuckydrawByCode($storeLuckydrawCode);
            if($storeLuckydraw->status != 'open' || $storeLuckydraw->is_active === 0)
            {
                throw new Exception('Winner selection is possible only if luckydraw is opened and active',403);
            }

            if($storeLuckydraw->perfectLuckydrawWinner()){
                if($storeLuckydraw->status != 'closed'){
                    $storeLuckydraw = $this->storeLuckydrawService->changeStoreLuckydrawStatus($storeLuckydraw,'closed');
                    Cache::forget($storeLuckydraw->store_luckydraw_code);
                    return sendErrorResponse('Luckydraw was closed',400);
                }
                    throw new Exception('Winner has already been selected in this luckydraw',400);
            }



            $limitedStoresForWinner = $this->storeLuckydrawWinnerService->getLimitedStoresWhileWinnerSelection($storeLuckydraw);
            $winnerStore = $this->storeLuckydrawWinnerService->createStoreLuckydrawWinner($storeLuckydraw);

            $stores = ($limitedStoresForWinner->push($winnerStore))->shuffle()->unique();
            $stores = $stores->map(function($store) use($winnerStore){
                $store->is_winner = ($store->store_code == $winnerStore->store_code) ? 1 : 0;
                return $store;
            });
           $totalStores = StoreResource::collection($stores);
            return sendSuccessResponse('Data Found', $totalStores);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function closeLuckydraw($storeLuckydrawCode,Request $request)
    {
        try{
            $storeLuckydraw = $this->storeLuckydrawService->findOrFailStoreLuckydrawByCode($storeLuckydrawCode);
           $storeLuckydraw = $this->storeLuckydrawService->changeStoreLuckydrawStatus($storeLuckydraw,'closed');
            Cache::forget($storeLuckydraw->store_luckydraw_code);
            $storeLuckydraw = new StoreLuckydrawDetailResource($storeLuckydraw);
            return sendSuccessResponse('Data Found',$storeLuckydraw);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}
