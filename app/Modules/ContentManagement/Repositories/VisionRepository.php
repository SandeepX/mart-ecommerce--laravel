<?php

namespace App\Modules\ContentManagement\Repositories;

use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\ContentManagement\Models\Vision;
use App\Modules\Application\Traits\UploadImage\ImageService;

class VisionRepository extends RepositoryAbstract
{
    use ImageService;
    public function findOrFailVisionMissionByCode($visionCode)
    {
        return Vision::where('vision_code',$visionCode)->firstOrFail();
    }

    public function getAllVisionMission()
    {
        return Vision::orderBy('is_active', 'desc')->latest()->get();
    }
    public function getLatestActiveVisionMission(){
        return Vision::select($this->select)
            ->where('is_active', 1)->latest()->first();
    }
    public function getAllLatestActiveVisionMission(){
        return Vision::where('is_active', 1)->latest()->get();
    }

    public function storeVisionMission($validatedData)
    {
        $validatedData['page_image'] = $this->storeImageInServer($validatedData['page_image'], Vision::PAGE_IMAGE_PATH);
        Vision::create($validatedData);
    }

    public function updateVisionMission($validatedData, $visionMission)
    {
        if(isset($validatedData['page_image'])){
            $this->deleteImageFromServer(Vision::PAGE_IMAGE_PATH, $visionMission->page_image);
            $validatedData['page_image'] = $this->storeImageInServer($validatedData['page_image'], Vision::PAGE_IMAGE_PATH);
        }

        $visionMission->update($validatedData);
    }
    public function deleteVisionMission($visionCode)
    {
        $visionCode->delete();
    }
}
