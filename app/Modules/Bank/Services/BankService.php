<?php


namespace App\Modules\Bank\Services;

use App\Modules\Bank\Repositories\BankRepository;
use DB;
use Exception;


class BankService
{

    private $bankRepository;

    public function __construct(BankRepository $bankRepository)
    {
        $this->bankRepository = $bankRepository;
    }

    public function getAllBanks()
    {
        $banks = $this->bankRepository->getAllBanks();
        return $banks;
    }

    public function findBankById($BankId)
    {
        return $this->bankRepository->findBankById($BankId);
    }


    public function findBankByCode($BankCode)
    {
        return $this->bankRepository->findBankByCode($BankCode);
    }

    public function findBankBySlug($BankSlug)
    {
        return $this->bankRepository->findBankBySlug($BankSlug);
    }

    public function findOrFailBankById($BankId)
    {
        return $this->bankRepository->findOrFailBankById($BankId);
    }


    public function findOrFailBankByCode($BankCode)
    {
        return $this->bankRepository->findOrFailBankByCode($BankCode);
    }

    public function findOrFailBankBySlug($BankSlug)
    {
        return $this->bankRepository->findOrFailBankBySlug($BankSlug);
    }


    public function storeBank($validated)
    {

        DB::beginTransaction();
        try {
            $Bank = $this->bankRepository->create($validated);
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $Bank;


    }

    public function updateBank($validated, $BankCode)
    {
        DB::beginTransaction();
        try {
            $Bank = $this->findBankByCode($BankCode);
            $this->bankRepository->update($validated, $Bank);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $Bank;
    }

    public function deleteBank($BankCode)
    {
        DB::beginTransaction();
        try {
            $Bank = $this->findBankByCode($BankCode);
            $this->bankRepository->delete($Bank);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $Bank;
    }
}
