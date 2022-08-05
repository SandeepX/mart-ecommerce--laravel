<?php


namespace App\Modules\SalesManager\Repositories;


use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\SalesManager\Models\ManagerSMISetting;

class ManagerSMISettingRepository extends RepositoryAbstract
{

    public function getAllManagerSMISettings()
    {
       return ManagerSMISetting::latest()->paginate(20);
    }

    public function findOrFailSMISettingByCode($MSMICode)
    {
        return ManagerSMISetting::where('msmi_settings_code',$MSMICode)->firstOrFail();
    }

    public function findLatestManagerSMISetting()
    {
        return ManagerSMISetting::latest()->first();
    }

    public function store($validatedData)
    {
       return ManagerSMISetting::create($validatedData)->fresh();
    }

    public function update($validatedData, $managerSMISettingDetail)
    {
        return $managerSMISettingDetail->update($validatedData);
    }

    public function delete($managerSMISettingDetail)
    {
        return $managerSMISettingDetail->delete();
    }

}
