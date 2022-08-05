<?php

namespace App\Modules\Types\Services;

use App\Modules\Types\Repositories\RejectionParamRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class RejectionParamService
{
    protected $rejectionParamRepository;

    public function __construct(RejectionParamRepository $rejectionParamRepository)
    {
        $this->rejectionParamRepository = $rejectionParamRepository;
    }
    public function getAllRejectionParams(){
        return $this->rejectionParamRepository->getAllRejectionParams();
    }



    public function findRejectionParamById($rejectionParamId, $with = [])
    {
        return $this->rejectionParamRepository->findRejectionParamById($rejectionParamId, $with);
    }

    public function findRejectionParamByCode($rejectionParamCode, $with = [])
    {
        return $this->rejectionParamRepository->findRejectionParamByCode($rejectionParamCode, $with);
    }

    public function findOrFailRejectionParamById($rejectionParamId, $with = [])
    {
        return $this->rejectionParamRepository->findRejectionParamById($rejectionParamId, $with);
    }

    public function findOrFailRejectionParamByCode($rejectionParamCode, $with = [])
    {
        return $this->rejectionParamRepository->findOrFailRejectionParamByCode($rejectionParamCode, $with);
    }

    public function storeRejectionParam($validated)
    {
        DB::beginTransaction();
        try {
            $rejectionParam = $this->rejectionParamRepository->storeRejectionParam($validated);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $rejectionParam;
    }

    public function updateRejectionParam($validated, $rejectionParamCode)
    {
        DB::beginTransaction();
        try {
            $rejectionParam = $this->rejectionParamRepository->findOrFailRejectionParamByCode($rejectionParamCode);
            $this->rejectionParamRepository->updateRejectionParam($validated, $rejectionParam);
            DB::commit();
            return $rejectionParam;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }

    public function deleteRejectionParam($rejectionParamCode)
    {
        DB::beginTransaction();
        try {
            $rejectionParam = $this->rejectionParamRepository->findOrFailRejectionParamByCode($rejectionParamCode);
            $rejectionParam = $this->rejectionParamRepository->delete($rejectionParam);
            DB::commit();
            return $rejectionParam;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }






}