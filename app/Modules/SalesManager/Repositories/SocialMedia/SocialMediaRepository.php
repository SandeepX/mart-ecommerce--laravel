<?php


namespace App\Modules\SalesManager\Repositories\SocialMedia;

use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\SalesManager\Models\SocialMedia;


class SocialMediaRepository extends RepositoryAbstract
{

    public function create($validatedData)
    {
       return SocialMedia::create($validatedData)->fresh();
    }

    public function findOrFailSocialMediaByCode($sm_code)
    {
        return SocialMedia::withCount('managerSMILinks')->where('sm_code',$sm_code)->firstOrfail();
    }



    public function getAllSocialMedia(){
        return SocialMedia::latest()->get();
    }

    public function getAllEnabledSocialMedia()
    {
        return SocialMedia::where('enabled_for_smi',1)->get();
    }

    public function toggleSocialMediaStatus($socialMediadetail)
    {
        return $socialMediadetail->update([
            'enabled_for_smi' => !$socialMediadetail['enabled_for_smi']
        ]);
    }

    public function update($validatedData,$socialMediaDetail)
    {
        return $socialMediaDetail->update($validatedData);
    }

    public function delete($socialMediaDetail)
    {
        return $socialMediaDetail->delete();
    }

}
