<?php

namespace App\Modules\ContentManagement\Repositories;

use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\ContentManagement\Models\TeamGallery;


class TeamGalleryRepository extends RepositoryAbstract
{
    use ImageService;
    public function findOrFailTeamGalleryByCode($teamGalleryCode)
    {
        return TeamGallery::where('team_gallery_code',$teamGalleryCode)->firstOrFail();
    }

    public function getAllTeamGallery()
    {
        return TeamGallery::orderBy('is_active', 'desc')->latest()->get();
    }

    public function getLatestActiveTeamGallery($paginate){
        return TeamGallery::select($this->select)
            ->where('is_active', 1)->latest()->paginate($paginate);
    }
    public function getAllLatestActiveTeamGallery(){
        return TeamGallery::where('is_active', 1)->latest()->get();
    }

    public function storeTeamGallery($validatedData)
    {
        $validatedData['image'] = $this->storeImageInServer($validatedData['image'], TeamGallery::TEAM_GALLERY_PATH);
        TeamGallery::create($validatedData);
    }

    public function updateTeamGallery($validatedData, $teamGallery)
    {
        if(isset($validatedData['image'])){
            $this->deleteImageFromServer(TeamGallery::TEAM_GALLERY_PATH, $teamGallery->image);
            $validatedData['image'] = $this->storeImageInServer($validatedData['image'], TeamGallery::TEAM_GALLERY_PATH);
        }

        $teamGallery->update($validatedData);
    }
    public function deleteTeamGallery($teamGallery)
    {
        $teamGallery->delete();
    }
}
