<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/24/2020
 * Time: 1:02 PM
 */

namespace App\Modules\Newsletter\Helpers;


use App\Modules\Newsletter\Models\Subscriber;

class SubscriberFilter
{

    public static function filterPaginatedSubscribers($filterParameters,$paginateBy,$with=[]){
        $subscribers = Subscriber::with($with)
            ->when(isset($filterParameters['subscriber']),function ($query) use($filterParameters){
                $query->where('email','like','%'.$filterParameters['subscriber'] . '%');
            })->when(isset($filterParameters['active']),function ($query) use($filterParameters){
                $query->where('is_active',$filterParameters['active']);
            });

        $paginateBy = isset($filterParameters['records_per_page'])  ? $filterParameters['records_per_page'] : $paginateBy;

        $subscribers= $subscribers->latest()->paginate($paginateBy);
        return $subscribers;
    }
}