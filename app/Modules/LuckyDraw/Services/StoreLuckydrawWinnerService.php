<?php

namespace App\Modules\LuckyDraw\Services;

use App\Modules\LuckyDraw\Helpers\StoreLuckydrawFilter;
use App\Modules\LuckyDraw\Repositories\StoreLuckydrawRepository;
use App\Modules\LuckyDraw\Repositories\StoreLuckydrawWinnerRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use Exception;

class StoreLuckydrawWinnerService
{

    private $storeLuckydrawWinnerRepository,$storeLuckydrawRepository;

    public function __construct(
        StoreLuckydrawWinnerRepository $storeLuckydrawWinnerRepository,
        StoreLuckydrawRepository $storeLuckydrawRepository
    ){
        $this->storeLuckydrawWinnerRepository = $storeLuckydrawWinnerRepository;
        $this->storeLuckydrawRepository = $storeLuckydrawRepository;

    }


    public function findStoreLuckydrawWinnerByCode($storeLuckydrawWinnerCode){
        return $this->storeLuckydrawWinnerRepository->findStoreLuckydrawWinnerByCode($storeLuckydrawWinnerCode);
    }

    public function findOrFailStoreLuckydrawWinnerByCode($storeLuckydrawWinnerCode){
        return $this->storeLuckydrawWinnerRepository->findOrFailStoreLuckydrawWinnerByCode($storeLuckydrawWinnerCode);
    }

    public function getAllStoreLuckydrawWinners()
    {
        return $this->storeLuckydrawWinnerRepository->getAllStoreLuckydrawWinners();
    }



    public function getLimitedStoresWhileWinnerSelection($storeLuckydraw){

        $cachedNotWinnerStores = Cache::get($storeLuckydraw->store_luckydraw_code,collect(0));

        if(count($cachedNotWinnerStores) > 0){
            $limitedNotWinnerStores = $cachedNotWinnerStores->take(99)->shuffle();
        }else{
            $limitedNotWinnerStores = StoreLuckydrawFilter::getNotWinnerStores(
                $storeLuckydraw,
                99,
                true
            );
            $limitedNotWinnerStores = collect($limitedNotWinnerStores);
        }
        return $limitedNotWinnerStores;
    }

    public function findActualWinnerStoreInLuckydraw($storeLuckydraw){
        $notWinnerStores = Cache::get($storeLuckydraw->store_luckydraw_code);
        $notFoundPrefixWinners = [];
        a: $prefixWinner = $this->storeLuckydrawWinnerRepository->getPrefixWinnerOfLuckydraw(
            $storeLuckydraw,
            $notFoundPrefixWinners
        );
        if($prefixWinner) {
            $winnerStoreCode =$prefixWinner->store_code;
        }
        else{
            $randomWinner = $notWinnerStores->random(1)->first();
            $winnerStoreCode = $randomWinner->store_code;
        }
        $winnerStore = $notWinnerStores->where('store_code',$winnerStoreCode)->first();
        if(!$winnerStore){
            array_push($notFoundPrefixWinners,$winnerStoreCode);
            goto a;
        }
        return $winnerStore;
    }



    public function createStoreLuckydrawWinner($storeLuckydraw){

        DB::beginTransaction();
        try {
           $winnerStore = $this->findActualWinnerStoreInLuckydraw($storeLuckydraw);
           $winnerData = [
               'store_luckydraw_code'=>$storeLuckydraw->store_luckydraw_code,
                'store_code'=>$winnerStore->store_code,
                'winner_eligibility'=>($winnerStore->eligibility),
            ];
            $this->storeLuckydrawWinnerRepository->create($winnerData);
            DB::commit();

            return $winnerStore;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }


//    public function deleteStoreLuckydrawWinner($storeLuckydrawWinnerCode)
//    {
//        DB::beginTransaction();
//        try {
//            $storeLuckydrawWinner = $this->storeLuckydrawWinnerRepository->findOrFailStoreLuckydrawWinnerByCode($storeLuckydrawWinnerCode);
//            $storeLuckydrawWinner = $this->storeLuckydrawWinnerRepository->delete($storeLuckydrawWinner);
//            DB::commit();
//        } catch (\Exception $exception) {
//            DB::rollBack();
//            throw $exception;
//        }
//        return $storeLuckydrawWinner;
//    }

}
