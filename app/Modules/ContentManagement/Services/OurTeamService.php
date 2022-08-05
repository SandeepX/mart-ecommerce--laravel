<?php

namespace App\Modules\ContentManagement\Services;


use App\Modules\ContentManagement\Repositories\OurTeamRepository;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\DocBlock\Description;

class OurTeamService
{
    private $ourTeamRepository;
    public function __construct(OurTeamRepository $ourTeamRepository)
    {
        $this->ourTeamRepository = $ourTeamRepository;
    }

    public function findOrFailOurTeamByCode($ourTeamCode)
    {
        return $this->ourTeamRepository->findOrFailOurTeamByCode($ourTeamCode);
    }

    public function getAllOurTeam()
    {
        return $this->ourTeamRepository->getAllOurTeam();
    }
    public function getLatestActiveOurTeam($select=['*']){
        return $this->ourTeamRepository->select($select)->getLatestActiveOurTeam();
    }
    public function getActiveTestimonial($select=['*']){
        return $this->ourTeamRepository->select($select)->getActiveTestimonial();

    }

    public function storeOurTeam($validatedData)
    {
        try{
            DB::beginTransaction();
            $this->ourTeamRepository->storeOurTeam($validatedData);
            DB::commit();
        }
        catch(\Exception $exception){
            DB::rollback();
            throw $exception;
        }

    }

    public function updateOurTeam($validatedData, $ourTeamCode)
    {
        if(!isset($validatedData['is_active']))
            $validatedData['is_active'] = 0;

        $ourTeam = $this->ourTeamRepository->findOrFailOurTeamByCode($ourTeamCode);
        $this->ourTeamRepository->updateOurTeam($validatedData, $ourTeam);
    }

    public function deleteOurTeam($ourTeamCode)
    {
        try{
            $ourTeam = $this->ourTeamRepository->findOrFailOurTeamByCode($ourTeamCode);
            $this->ourTeamRepository->deleteOurTeam($ourTeam);
        }catch (\Exception $exception){
            throw $exception;
        }



    }
}
