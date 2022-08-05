<?php

namespace App\Modules\Types\Services;

use App\Modules\Types\Repositories\CancellationParamRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class CancellationParamService
{
    protected $cancellationParamRepository;

    public function __construct(CancellationParamRepository $cancellationParamRepository)
    {
        $this->cancellationParamRepository = $cancellationParamRepository;
    }
    public function getAllCancellationParams(){
        return $this->cancellationParamRepository->getAllCancellationParams();
    }



    public function findCancellationParamById($cancellationParamId, $with = [])
    {
        return $this->cancellationParamRepository->findCancellationParamById($cancellationParamId, $with);
    }

    public function findCancellationParamByCode($cancellationParamCode, $with = [])
    {
        return $this->cancellationParamRepository->findCancellationParamByCode($cancellationParamCode, $with);
    }

    public function findOrFailCancellationParamById($cancellationParamId, $with = [])
    {
        return $this->cancellationParamRepository->findCancellationParamById($cancellationParamId, $with);
    }

    public function findOrFailCancellationParamByCode($cancellationParamCode, $with = [])
    {
        return $this->cancellationParamRepository->findOrFailCancellationParamByCode($cancellationParamCode, $with);
    }

    public function storeCancellationParam($validated)
    {
        DB::beginTransaction();
        try {
            $cancellationParam = $this->cancellationParamRepository->storeCancellationParam($validated);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $cancellationParam;
    }

    public function updateCancellationParam($validated, $cancellationParamCode)
    {
        DB::beginTransaction();
        try {
            $cancellationParam = $this->cancellationParamRepository->findOrFailCancellationParamByCode($cancellationParamCode);
            $this->cancellationParamRepository->updateCancellationParam($validated, $cancellationParam);
            DB::commit();
            return $cancellationParam;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }

    public function deleteCancellationParam($cancellationParamCode)
    {
        DB::beginTransaction();
        try {
            $cancellationParam = $this->cancellationParamRepository->findOrFailCancellationParamByCode($cancellationParamCode);
            $cancellationParam = $this->cancellationParamRepository->delete($cancellationParam);
            DB::commit();
            return $cancellationParam;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }






}