<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/24/2020
 * Time: 11:15 AM
 */

namespace App\Modules\User\Helpers;


use App\Modules\User\Models\User;

class UserFilter
{

    public static function filterPaginatedUsers($filterParameters,$paginateBy,$with=[]){

        $users = User::with($with)
            ->when(isset($filterParameters['user_type']),function ($query) use($filterParameters){
                $query->whereHas('userType', function ($query) use ($filterParameters) {
                    $query->where('user_type_code',$filterParameters['user_type']);
                });
            })->when(isset($filterParameters['user_name']),function ($query) use($filterParameters){
                $query->where('name','like','%'.$filterParameters['user_name'] . '%');
            })->when(isset($filterParameters['email']),function ($query) use($filterParameters){
                $query->where('login_email','like','%'.$filterParameters['email'] . '%');
            });


        $paginateBy = isset($filterParameters['records_per_page'])  ? $filterParameters['records_per_page'] : $paginateBy;

        $users= $users->latest()->paginate($paginateBy);
        return $users;
    }
}
