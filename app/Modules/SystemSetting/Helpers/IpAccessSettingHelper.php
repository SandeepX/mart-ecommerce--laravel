<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/4/2020
 * Time: 3:39 PM
 */

namespace App\Modules\SystemSetting\Helpers;


use App\Modules\SystemSetting\Models\IpAccessSetting;

class IpAccessSettingHelper
{

    public static function getAllowedIpAddresses(){

        return IpAccessSetting::where('is_allowed',1)->pluck('ip_address')->toArray();
    }

    public static function filterPaginatedIpAddresses($filterParameters,$paginateBy,$with=[]){
        $addresses = IpAccessSetting::with($with)
            ->when(isset($filterParameters['ip_name']),function ($query) use($filterParameters){
                $query->where('ip_name','like','%'.$filterParameters['ip_name'] . '%');
            })->when(isset($filterParameters['ip_address']),function ($query) use($filterParameters){
                $query->where('ip_address','like','%'.$filterParameters['ip_address'] . '%');
            })->when(isset($filterParameters['allowed']),function ($query) use($filterParameters){
                $query->where('is_allowed',$filterParameters['allowed']);
            });


        $paginateBy = isset($filterParameters['records_per_page'])  ? $filterParameters['records_per_page'] : $paginateBy;

        $addresses= $addresses->latest()->paginate($paginateBy);
        return $addresses;
    }
}