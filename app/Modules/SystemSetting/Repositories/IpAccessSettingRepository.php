<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/4/2020
 * Time: 2:20 PM
 */

namespace App\Modules\SystemSetting\Repositories;


use App\Modules\SystemSetting\Models\IpAccessSetting;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class IpAccessSettingRepository
{
    public function getAll($allowed=false){

        $ipAccesses = IpAccessSetting::query();

        if ($allowed){
            $ipAccesses = $ipAccesses->allowed();
        }

        return $ipAccesses->latest()->get();
    }

    public function findOrFailByCode($code,$with=[]){

        $ipAccesses = IpAccessSetting::with($with)->where('ip_access_code',$code)->first();

        if (!$ipAccesses){
            throw new ModelNotFoundException('Ip access setting not found for the code');
        }

        return $ipAccesses;
    }

    public function save($validatedData){
        return IpAccessSetting::create($validatedData)->fresh();
    }

    public function update(IpAccessSetting $ipAccess,$data){
        $ipAccess->update($data);
        return $ipAccess->fresh();
    }

    public function delete(IpAccessSetting $ipAccess) {
        $ipAccess->delete();
        return $ipAccess;
    }
}