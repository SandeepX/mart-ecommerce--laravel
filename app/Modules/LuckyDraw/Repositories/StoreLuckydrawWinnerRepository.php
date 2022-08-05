<?php

namespace App\Modules\LuckyDraw\Repositories;

use App\Modules\LuckyDraw\Models\PrefixWinner;
use App\Modules\LuckyDraw\Models\StoreLuckydrawWinner;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Exception;

class StoreLuckydrawWinnerRepository
{


    public function findStoreLuckydrawWinnerByCode($storeLuckydrawWinnerCode)
    {
        return StoreLuckydrawWinner::where('store_luckydraw_winner_code', $storeLuckydrawWinnerCode)->first();
    }

    public function findOrFailStoreLuckydrawWinnerByCode($storeLuckydrawWinnerCode)
    {
        if($storeLuckydrawWinner = $this->findStoreLuckydrawWinnerByCode($storeLuckydrawWinnerCode))
        {
            return $storeLuckydrawWinner;
        }
        throw new ModelNotFoundException('No Such StoreLuckydrawWinner Found !');
    }

    public function getAllStoreLuckydrawWinners()
    {
        return StoreLuckydrawWinner::latest()->get();
    }

    public function create($validatedData)
    {

        try {
            return StoreLuckydrawWinner::create($validatedData)->fresh();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public  function getPrefixWinnerOfLuckydraw($storeLuckydraw,$notFoundPrefixWinners = [])
    {

        $notFeasibleWinners = StoreLuckydrawWinner::where('store_luckydraw_code',$storeLuckydraw->store_luckydraw_code)
            ->where('winner_eligibility',0)->pluck('store_code')->toArray();

        $notFeasibleWinners = $notFeasibleWinners + $notFoundPrefixWinners;

         return PrefixWinner::whereNotIn(
            'store_code',$notFeasibleWinners
        )->where('store_luckydraw_code',$storeLuckydraw->store_luckydraw_code)
             ->orderBy('sort_order','asc')
            ->first();
    }

    public function selectRandomWinner($stores)
    {
        return $stores->random(1)->first();
    }

//    public function delete($storeLuckydrawWinner)
//    {
//        $storeLuckydrawWinner->delete();
//        return $storeLuckydrawWinner;
//    }

}
