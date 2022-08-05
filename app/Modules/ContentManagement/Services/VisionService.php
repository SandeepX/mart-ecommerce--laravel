<?php

namespace App\Modules\ContentManagement\Services;

use App\Modules\ContentManagement\Repositories\VisionRepository;
use Illuminate\Support\Facades\DB;

class VisionService
{
    private $visionRepository;
    public function __construct(VisionRepository $visionRepository)
    {
        $this->visionRepository = $visionRepository;
    }

    public function findOrFailVisionMissionByCode($aboutUsCode)
    {
        return $this->visionRepository->findOrFailVisionMissionByCode($aboutUsCode);
    }

    public function getAllVisionMission()
    {
        return $this->visionRepository->getAllVisionMission();
    }
    public function getLatestActiveVisionMission($select=['*'],$with=[]){
        return $this->visionRepository->with($with)->select($select)->getLatestActiveVisionMission();
    }

    public function storeVisionMission($validatedData)
    {
        try{
            DB::beginTransaction();
            $this->visionRepository->storeVisionMission($validatedData);
            DB::commit();
        }
        catch(\Exception $exception){
            DB::rollback();
            throw $exception;
        }

    }

    public function updateVisionMission($validatedData, $visionMission)
    {
        if(!isset($validatedData['is_active']))
            $validatedData['is_active'] = 0;
        $activeVisionMission = $this->visionRepository->getAllLatestActiveVisionMission();
        if($activeVisionMission->count() == 1 && $validatedData['is_active']==0){
            throw new \Exception("Sorry.. You must need atleast one active Vision Mission");
        }
        $visionMission = $this->visionRepository->findOrFailVisionMissionByCode($visionMission);
        $this->visionRepository->updateVisionMission($validatedData, $visionMission);
    }

    public function deleteVisionMission($visionMissionCode)
    {
        try {
            $activeVisionMission = $this->visionRepository->getAllLatestActiveVisionMission();
            if ($activeVisionMission->count() == 1) {
                throw new \Exception("Sorry.. You must need atleast one active vision mission");
            }
            $visionMission = $this->visionRepository->findOrFailVisionMissionByCode($visionMissionCode);
            $this->visionRepository->deleteVisionMission($visionMission);
        }
        catch(\Exception $exception){
            throw $exception;
        }

    }
}
