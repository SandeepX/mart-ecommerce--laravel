<?php

namespace App\Modules\ContentManagement\Services;

use App\Modules\ContentManagement\Repositories\AboutUsRepository;
use Illuminate\Support\Facades\DB;

class AboutUsService
{
    private $aboutUsRepository;
    public function __construct(AboutUsRepository $aboutUsRepository)
    {
        $this->aboutUsRepository = $aboutUsRepository;
    }

    public function findOrFailAboutUsByCode($aboutUsCode)
    {
        return $this->aboutUsRepository->findOrFailAboutUsByCode($aboutUsCode);
    }

    public function getAllAboutUs()
    {
        return $this->aboutUsRepository->getAllAboutUs();
    }
    public function getLatestActiveAboutUs($select=['*']){
        return $this->aboutUsRepository->select($select)->getLatestActiveAboutUs();
    }

    public function storeAboutUs($validatedAboutUs)
    {
        try{
            DB::beginTransaction();
            $this->aboutUsRepository->storeAboutUs($validatedAboutUs);
            DB::commit();
        }
        catch(\Exception $exception){
            DB::rollback();
            throw $exception;
        }

    }

    public function updateAboutUs($validatedAboutUs, $aboutUsCode)
    {
        if(!isset($validatedAboutUs['is_active']))
            $validatedAboutUs['is_active'] = 0;

            $activeAboutUs=$this->aboutUsRepository->getAllLatestActiveAboutUs();
        if($activeAboutUs->count() == 1  && $validatedAboutUs['is_active'] ==0){
            throw new \Exception("Sorry.. You must need atleast one active About Us");
        }
        $aboutUs = $this->aboutUsRepository->findOrFailAboutUsByCode($aboutUsCode);
        $this->aboutUsRepository->updateAboutUs($validatedAboutUs, $aboutUs);
    }

    public function deleteAboutUs($aboutUsCode)
    {
        try{
            $about=$this->aboutUsRepository->getAllLatestActiveAboutUs()->count();
            if($about == 1)
            {
                throw new \Exception("Sorry.. You must need atleast one active About Us");
            }
            $aboutUs = $this->aboutUsRepository->findOrFailAboutUsByCode($aboutUsCode);
            $this->aboutUsRepository->deleteAboutUs($aboutUs);
        }catch (\Exception $exception){
            throw $exception;
        }



    }
}
