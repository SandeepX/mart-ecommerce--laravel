<?php

namespace App\Modules\ContentManagement\Repositories;

use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\ContentManagement\Models\OurTeam;
use App\Modules\ContentManagement\Resources\OurTeamResource;


class OurTeamRepository extends RepositoryAbstract
{
    use ImageService;
    public function findOrFailOurTeamByCode($ourTeamCode)
    {
        return OurTeam::where('our_team_code',$ourTeamCode)->firstOrFail();
    }

    public function getAllOurTeam()
    {
        return OurTeam::orderBy('is_active', 'desc')->latest()->get();
    }

    public function getLatestActiveOurTeam(){
        return OurTeam::select($this->select)
            ->where('is_active', 1)->latest()->get();
    }
    public function getActiveTestimonial(){
        return OurTeam::select($this->select)->whereNotNull('message')->where('is_active',1)->latest()->get();
    }
    public function getAllLatestActiveOurTeam(){
        return OurTeam::where('is_active', 1)->latest()->get();
    }

    public function storeOurTeam($validatedData)
    {
        $validatedData['image'] = $this->storeImageInServer($validatedData['image'], OurTeam::TEAM_IMAGE_PATH);
        OurTeam::create($validatedData);
    }

    public function updateOurTeam($validatedData, $ourTeam)
    {
        if(isset($validatedData['image'])){
            $this->deleteImageFromServer(OurTeam::TEAM_IMAGE_PATH, $ourTeam->image);
            $validatedData['image'] = $this->storeImageInServer($validatedData['image'], OurTeam::TEAM_IMAGE_PATH);
        }

        $ourTeam->update($validatedData);
    }
    public function deleteOurTeam($ourTeam)
    {
        $ourTeam->delete();
    }
}
