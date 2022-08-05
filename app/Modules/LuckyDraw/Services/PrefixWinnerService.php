<?php

namespace App\Modules\LuckyDraw\Services;

use App\Modules\LuckyDraw\Repositories\PrefixWinnerRepository;
use App\Modules\LuckyDraw\Repositories\StoreLuckydrawRepository;
use Illuminate\Support\Facades\DB;

use Exception;

class PrefixWinnerService
{

    private $prefixWinnerRepository,$storeLuckydrawRepository;

    public function __construct(
        PrefixWinnerRepository $prefixWinnerRepository,
        StoreLuckydrawRepository $storeLuckydrawRepository
    ){
        $this->prefixWinnerRepository = $prefixWinnerRepository;
        $this->storeLuckydrawRepository = $storeLuckydrawRepository;

    }


    public function findPrefixWinnerByCode($prefixWinnerCode){
        return $this->prefixWinnerRepository->findPrefixWinnerByCode($prefixWinnerCode);
    }

    public function findOrFailPrefixWinnerByCode($prefixWinnerCode){
        return $this->prefixWinnerRepository->findOrFailPrefixWinnerByCode($prefixWinnerCode);
    }

    public function getAllPrefixWinners()
    {
        return $this->prefixWinnerRepository->getAllPrefixWinners();
    }

    public function storePrefixWinner($validatedData){
        DB::beginTransaction();
        try {
            foreach($validatedData['store_code'] as $key=>$value)
            {
                $validatedData['store_code'] = $value;
                $prefixWinner = $this->prefixWinnerRepository->create($validatedData);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $prefixWinner;
    }

    public function updatePrefixWinner($validatedData, $prefixWinnerCode)
    {
        DB::beginTransaction();

        try {
            $prefixWinner = $this->prefixWinnerRepository->findOrFailPrefixWinnerByCode($prefixWinnerCode);
            if(isset($prefixWinner->storeLuckydraw))
            {
                if($prefixWinner->storeLuckydraw->status === 'closed')
                {
                    throw new Exception('Closed Store LuckyDraw Can not be edited !');
                }
            }

            $prefixWinner = $this->prefixWinnerRepository->update($validatedData,$prefixWinner);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $prefixWinner;
    }

    public function deletePrefixWinner($prefixWinnerCode)
    {
        DB::beginTransaction();
        try {
            $prefixWinner = $this->prefixWinnerRepository->findOrFailPrefixWinnerByCode($prefixWinnerCode);
            if(isset($prefixWinner->storeLuckydraw))
            {
                if($prefixWinner->storeLuckydraw->status === 'closed')
                {
                    throw new Exception('Closed Store LuckyDraw Can not be deleted !');
                }
            }
            $prefixWinner = $this->prefixWinnerRepository->delete($prefixWinner);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $prefixWinner;
    }

    public function changePackageDisplayOrder($storeLuckydrawCode,$sortOrdersToChange)
    {
        try{

            DB::beginTransaction();
            $storeLuckydraw = $this->storeLuckydrawRepository->findOrFailStoreLuckydrawByCode($storeLuckydrawCode);
            foreach ($storeLuckydraw->prefixWinners as $prefixWinner) {
                $prefixWinner->timestamps = false; // To disable update_at field updation
                $id = $prefixWinner->id;

                foreach ($sortOrdersToChange as $order) {
                    if ($order['id'] == $id) {
                        $prefixWinner->update(['sort_order' => $order['position']]);
                    }
                }
            }
            DB::commit();
            return $prefixWinner;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}
