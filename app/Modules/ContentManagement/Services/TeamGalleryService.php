<?php

namespace App\Modules\ContentManagement\Services;


use App\Modules\ContentManagement\Repositories\TeamGalleryRepository;
use Illuminate\Support\Facades\DB;

class TeamGalleryService
{
    private $teamGalleryRepository;
    public function __construct(TeamGalleryRepository $teamGalleryRepository)
    {
        $this->teamGalleryRepository = $teamGalleryRepository;
    }

    public function findOrFailTeamGalleryByCode($teamGalleryCode)
    {
        return $this->teamGalleryRepository->findOrFailTeamGalleryByCode($teamGalleryCode);
    }

    public function getAllTeamGallery()
    {
        return $this->teamGalleryRepository->getAllTeamGallery();
    }
    public function getLatestActiveTeamGallery($select=['*'],$paginate=1){
        return $this->teamGalleryRepository->select($select)->getLatestActiveTeamGallery($paginate);
    }

    public function storeTeamGallery($validatedData)
    {
        try{
            DB::beginTransaction();
            $this->teamGalleryRepository->storeTeamGallery($validatedData);
            DB::commit();
        }
        catch(\Exception $exception){
            DB::rollback();
            throw $exception;
        }

    }

    public function updateTeamGallery($validatedData, $teamGalleryCode)
    {
        if(!isset($validatedData['is_active']))
            $validatedData['is_active'] = 0;

        $teamGallery = $this->teamGalleryRepository->findOrFailTeamGalleryByCode($teamGalleryCode);
        $this->teamGalleryRepository->updateTeamGallery($validatedData, $teamGallery);
    }

    public function deleteTeamGallery($teamGalleryCode)
    {
        try{
            $teamGallery = $this->teamGalleryRepository->findOrFailTeamGalleryByCode($teamGalleryCode);
            $this->teamGalleryRepository->deleteTeamGallery($teamGallery);
        }catch (\Exception $exception){
            throw $exception;
        }
    }
}
