<?php
namespace App\Modules\Career\Services;

use App\Modules\Career\Repositories\CandidateRepository;
use Illuminate\Support\Facades\DB;

class CandidateService{
    private $candidateRepository;

    public function __construct(CandidateRepository $candidateRepository)
    {
        $this->candidateRepository=$candidateRepository;
    }

    public function getAllFilterCandidate(){
         return $this->candidateRepository->getAllCandidate();
    }

    public function createCandidate($validatedData){
        try{
            DB::beginTransaction();
            $candidate=$this->candidateRepository->createCandidate($validatedData);
            DB::commit();
            return $candidate;
        }
        catch(Exception $exception){
            DB::rollback();
            throw $exception;
        }
    }
    public function showCandidateDetail($candidateCode){
        try{
            return $this->candidateRepository->showCandidateDetail($candidateCode);
        }
        catch(\Exception $exception){
            throw $exception;
        }
    }
}
