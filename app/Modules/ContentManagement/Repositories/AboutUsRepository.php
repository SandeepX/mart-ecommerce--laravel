<?php

namespace App\Modules\ContentManagement\Repositories;

use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\ContentManagement\Models\AboutUs;
use App\Modules\Application\Traits\UploadImage\ImageService;
use mysql_xdevapi\Exception;

class AboutUsRepository extends RepositoryAbstract
{
    use ImageService;
    public function findOrFailAboutUsByCode($aboutUsCode)
    {
        return AboutUs::where('aboutUs_code',$aboutUsCode)->firstOrFail();
    }

    public function getAllAboutUs()
    {
        return AboutUs::orderBy('is_active', 'desc')->latest()->get();
    }

    public function getLatestActiveAboutUs(){
        return AboutUs::select($this->select)
            ->where('is_active', 1)->latest()->first();
//        if(!$aboutUs){
//            throw new Exception('No Active Us Found');
//        }
//        return $aboutUs;
    }
    public function getAllLatestActiveAboutUs(){
        return AboutUs::where('is_active', 1)->latest()->get();
    }

    public function storeAboutUs($validatedData)
    {
        $validatedData['page_image'] = $this->storeImageInServer($validatedData['page_image'], AboutUs::PAGE_IMAGE_PATH);
        $validatedData['ceo_image'] = $this->storeImageInServer($validatedData['ceo_image'], AboutUs::CEO_IMAGE_PATH);
        AboutUs::create($validatedData);
    }

    public function updateAboutUs($validatedAboutUs, $aboutUs)
    {
        if(isset($validatedAboutUs['page_image'])){
            $this->deleteImageFromServer(AboutUs::PAGE_IMAGE_PATH, $aboutUs->page_image);
            $validatedAboutUs['page_image'] = $this->storeImageInServer($validatedAboutUs['page_image'], AboutUs::PAGE_IMAGE_PATH);
        }
        if(isset($validatedAboutUs['ceo_image'])){
            $this->deleteImageFromServer(AboutUs::CEO_IMAGE_PATH, $aboutUs->ceo_image);
            $validatedAboutUs['ceo_image'] = $this->storeImageInServer($validatedAboutUs['ceo_image'], AboutUs::CEO_IMAGE_PATH);
        }
        $aboutUs->update($validatedAboutUs);
    }
    public function deleteAboutUs($aboutUsCode)
    {
        $aboutUsCode->delete();
    }
}
