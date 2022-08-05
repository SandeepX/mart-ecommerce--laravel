<?php


namespace App\Modules\Impersonate\Repositories;


use App\Modules\Impersonate\Models\Impersonate;

class ImpersonateRepository
{
    public function getImpersonateDataByUUID($UUID)
    {

        return Impersonate::where('uuid',$UUID)
            ->first();
    }

    public function store($validatedData)
    {
        return Impersonate::create($validatedData)->fresh();
    }

}
