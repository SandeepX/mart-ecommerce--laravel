<?php

namespace App\Modules\LuckyDraw\Repositories;

use App\Modules\LuckyDraw\Models\PrefixWinner;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Exception;

class PrefixWinnerRepository
{


    public function findPrefixWinnerByCode($prefixWinnerCode)
    {
        return PrefixWinner::where('prefix_winner_code', $prefixWinnerCode)->first();
    }

    public function getPrefixWinnersByStoreLuckDrawCode($storeLuckyDrawCode){
        return PrefixWinner::where('store_luckydraw_code',$storeLuckyDrawCode)->get();
    }

    public function findOrFailPrefixWinnerByCode($prefixWinnerCode)
    {
        if($prefixWinner = $this->findPrefixWinnerByCode($prefixWinnerCode))
        {
            return $prefixWinner;
        }
        throw new ModelNotFoundException('No Such PrefixWinner Found !');
    }

    public function getAllPrefixWinners()
    {
        return PrefixWinner::get();
    }

    public function create($validatedData)
    {

        try {

            return PrefixWinner::create($validatedData)->fresh();
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function update($validatedData, $prefixWinner)
    {

        try {

            $prefixWinner->update($validatedData);
            return $prefixWinner->fresh();
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function delete($prefixWinner)
    {
        $prefixWinner->delete();
        $prefixWinner->deleted_by = getAuthUserCode();
        $prefixWinner->save();
        return $prefixWinner;
    }


}
