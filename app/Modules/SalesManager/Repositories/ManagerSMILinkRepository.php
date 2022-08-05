<?php


namespace App\Modules\SalesManager\Repositories;


use App\Modules\SalesManager\Models\ManagerSMILink;

class ManagerSMILinkRepository
{
    public function getManagerSMILinksByMSMICode($msmi_code)
    {
        return ManagerSMILink::where('msmi_code',$msmi_code)->get();
    }

    public function store($validatedData)
    {
        return ManagerSMILink::create($validatedData)->fresh();
    }

    public function deleteManagerSMILinkCollection($managerSMILink)
    {
        return $managerSMILink->each->delete();
    }

}
