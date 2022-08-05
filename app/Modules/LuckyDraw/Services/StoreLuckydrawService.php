<?php

namespace App\Modules\LuckyDraw\Services;

use App\Modules\LuckyDraw\Repositories\PrefixWinnerRepository;
use App\Modules\LuckyDraw\Repositories\StoreLuckydrawRepository;
use Illuminate\Support\Facades\DB;

use Exception;

class StoreLuckydrawService
{

    private $storeLuckydrawRepository,$storeLuckydrawWinnerService;
    private $prefixWinnerRepository;

    public function __construct(
        StoreLuckydrawRepository $storeLuckydrawRepository,
        StoreLuckydrawWinnerService $storeLuckydrawWinnerService,
        PrefixWinnerRepository $prefixWinnerRepository
    ){
        $this->storeLuckydrawRepository = $storeLuckydrawRepository;
        $this->storeLuckydrawWinnerService = $storeLuckydrawWinnerService;
        $this->prefixWinnerRepository = $prefixWinnerRepository;

    }


    public function findStoreLuckydrawByCode($storeLuckydrawCode){
        return $this->storeLuckydrawRepository->findStoreLuckydrawByCode($storeLuckydrawCode);
    }

    public function findOrFailStoreLuckydrawByCode($storeLuckydrawCode){
        return $this->storeLuckydrawRepository->findOrFailStoreLuckydrawByCode($storeLuckydrawCode);
    }

    public function getAllLuckydraws()
    {
        return $this->storeLuckydrawRepository->getAllLuckydraws();
    }
    public function getAllPaginatedLuckydraws($status,$perPage)
    {
        return $this->storeLuckydrawRepository->getAllPaginatedLuckydraws($status,$perPage);
    }

    public function storePrize($validatedData){
        DB::beginTransaction();
        try {

            $storeLuckydraw = $this->storeLuckydrawRepository->create($validatedData);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $storeLuckydraw;
    }

    public function updatePrize($validatedData, $storeLuckydrawCode)
    {
        DB::beginTransaction();

        try {
            $storeLuckydraw = $this->storeLuckydrawRepository->findOrFailStoreLuckydrawByCode($storeLuckydrawCode);
            if($storeLuckydraw->status === 'closed')
            {
                throw new Exception('Closed Store LuckyDraw Can not be updated !');
            }
//            dd(count($validatedData['terms']));
//            if(count($validatedData['terms']) > 1)
//            {
//                $validatedData['terms'] = json_encode(array_merge($))
//            }
            $storeLuckydraw = $this->storeLuckydrawRepository->update($validatedData,$storeLuckydraw);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $storeLuckydraw;
    }

    public function deletePrize($storeLuckydrawCode)
    {
        DB::beginTransaction();
        try {
            $storeLuckydraw = $this->storeLuckydrawRepository->findOrFailStoreLuckydrawByCode($storeLuckydrawCode);
            if($storeLuckydraw->status === 'closed')
            {
                throw new Exception('Closed Store LuckyDraw Can not be deleted !');
            }
            $prefixWinners = $this->prefixWinnerRepository->getPrefixWinnersByStoreLuckDrawCode($storeLuckydrawCode);

            foreach ($prefixWinners as $prefixWinner){
                $this->prefixWinnerRepository->delete($prefixWinner);
            }
            $storeLuckydraw = $this->storeLuckydrawRepository->delete($storeLuckydraw);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $storeLuckydraw;
    }

    public function changeStoreLuckydrawStatus($storeLuckydrawDetail,$status)
    {
        try{

            DB::beginTransaction();
//            if($status === 'open')
//            {
//                throw new Exception('Following operation can not be done');
//            }
            $storeLuckydrawDetail = $this->storeLuckydrawRepository->changeStoreLuckydrawStatus($storeLuckydrawDetail,$status);
            DB::commit();
            return $storeLuckydrawDetail;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function changeStoreLuckyDrawActiveStatus($SLCode,$status)
    {
        try{
            $storeLuckydraw =  $this->storeLuckydrawRepository->findOrFailStoreLuckydrawByCode($SLCode);
            if($status == 'active'){
                $status = 1;
            }elseif($status == 'inactive'){
                $status = 0;
            }
            //dd($status);
            DB::beginTransaction();
            $storeLuckydraw = $this->storeLuckydrawRepository->changeStoreLuckyDrawActiveStatus($storeLuckydraw,$status);
            DB::commit();
            return $storeLuckydraw;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}
