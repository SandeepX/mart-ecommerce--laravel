<?php


namespace App\Modules\SystemSetting\Services\SocialMedia;

use App\Modules\SystemSetting\Repositories\SocialMedia\SocialMediaRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class SocialMediaService
{

    private $socialMediaRepo;

    public function __construct(SocialMediaRepository $socialMediaRepo)
    {
        $this->socialMediaRepo = $socialMediaRepo;
    }

    public function getAllSocialMedias()
    {
        return $this->socialMediaRepo->getAllSocialMedia();
    }

    public function getAllEnabledSocialMedia()
    {
        try{
            $enabledSocialMedia = $this->socialMediaRepo->getAllEnabledSocialMedia();
            return $enabledSocialMedia;
        }catch(Exception $exception){
            throw  $exception;
        }
    }

    public function getSocialMediaByCode($SMCode)
    {
        return $this->socialMediaRepo->findOrFailSocialMediaByCode($SMCode);
    }

    public function store($validatedData)
    {
        DB::beginTransaction();

        $validatedData['enabled_for_smi'] = isset($validatedData['enabled_for_smi']) ? $validatedData['enabled_for_smi']: 0;

        try {
            $socialMedia = $this->socialMediaRepo->create($validatedData);
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $socialMedia;

    }

    public function deleteSocialMedia($SMCode)
    {
        DB::beginTransaction();
        try{
            $socialMediaDetail = $this->getSocialMediaByCode($SMCode);
            $this->socialMediaRepo->delete($socialMediaDetail);

            DB::commit();
            return true;

        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function update($validatedData, $socialMediaCode)
    {
        DB::beginTransaction();
        $validatedData['enabled_for_smi'] = isset($validatedData['enabled_for_smi']) ?
            $validatedData['enabled_for_smi']: 0;
        try {
            $socialMediaDetail = $this->getSocialMediaByCode($socialMediaCode);
            $this->socialMediaRepo->update($validatedData, $socialMediaDetail);

            DB::commit();
            return true;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }

    }

    public function toggleEnableStatusForSMI($SMCode)
    {
        DB::beginTransaction();
        try{
            $socialMedia = $this->getSocialMediaByCode($SMCode);
            $status = $this->socialMediaRepo->toggleSocialMediaStatus($socialMedia);

            DB::commit();
            return $status;

        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }


}


