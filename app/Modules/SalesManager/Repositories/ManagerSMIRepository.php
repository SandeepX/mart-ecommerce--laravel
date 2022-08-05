<?php


namespace App\Modules\SalesManager\Repositories;


use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\SalesManager\Models\ManagerSMI;

class ManagerSMIRepository extends RepositoryAbstract
{

    public function store($validatedData)
    {
        return ManagerSMI::create($validatedData)->fresh();
    }

    public function update($validatedData,$managerSMIdetail)
    {
        return $managerSMIdetail->update([
            'allow_edit' => $validatedData['allow_edit'],
            'status' => $validatedData['status']
        ]);
    }

    public function getAllManagerSMI()
    {
        return ManagerSMI::latest()->paginate(ManagerSMI::RECORDS_PER_PAGE);
    }

    public function findManagerSMIDetailByCode($msmi_code)
    {
        return ManagerSMI::where('msmi_code',$msmi_code)->first();
    }

    public function findSMIManagerDetailByManagerCode($manager_code)
    {
        return ManagerSMI::where('manager_code',$manager_code)->latest()->first();
    }

    public function findExceptRejectedSMIDetailByManagerCode($manager_code)
    {
        return ManagerSMI::where('manager_code',$manager_code)
            ->where('status','!=','rejected')
            ->latest()
            ->first();
    }

    public function findSMIManagerDetailForUpdate()
    {
        return ManagerSMI::where('manager_code',getAuthManagerCode())
//            ->where('status','rejected')
            ->where('is_active',1)
            ->where('allow_edit',1)
            ->first();
    }

    public function toggleIsActiveStatus($managerSMIDetail)
    {
        return $managerSMIDetail->update([
            'is_active' => !$managerSMIDetail['is_active']
        ]);
    }

    public function getManagerSMIAllDetails($msmi_code)
    {
        return ManagerSMI::with($this->with)
            ->select($this->select)
            ->where('msmi_code',$msmi_code)
            ->first();
    }

    public function changeStatus($managerSMIDetail,$validatedData)
    {
        return $managerSMIDetail->update([
            'status' => $validatedData['status'],
            'remarks' => $validatedData['remarks']
        ]);
    }

    public function toggleAllowEditStatus($managerSMIDetail,$validatedData)
    {
        return $managerSMIDetail->update([
//            'status' => $validatedData['status'],
            'allow_edit' => $validatedData['allow_edit'],
            'allow_edit_remarks' => $validatedData['allow_edit_remarks'],
            'edit_allowed_by' => getAuthUserCode(),
        ]);
    }

}
