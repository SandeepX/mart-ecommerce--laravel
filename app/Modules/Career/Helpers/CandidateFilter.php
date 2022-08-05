<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/24/2020
 * Time: 11:15 AM
 */

namespace App\Modules\Career\Helpers;


use App\Modules\Career\Models\Candidate;

class CandidateFilter
{

    public static function filterPaginatedCandidates($filterParameters,$paginateBy,$with=[]){
//    dd($filterParameters);
        $candidates = Candidate::with($with)
            ->when(isset($filterParameters['name']),function($query) use($filterParameters){
                $query->where('name','like','%'. $filterParameters['name']. '%');
            })
            ->when(isset($filterParameters['careerCode']),function ($query) use($filterParameters){
                $query->whereHas('careers', function ($query) use ($filterParameters) {
                    $query->where('career_code',$filterParameters['careerCode']);
                });
            })
            ->when(isset($filterParameters['appliedFrom']), function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '>=', date('y-m-d', strtotime($filterParameters['appliedFrom'])));
            })->when(isset($filterParameters['appliedTo']), function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '<=', date('y-m-d', strtotime($filterParameters['appliedTo'])));
            })
            ->when(isset($filterParameters['appliedDate']),function ($query) use($filterParameters){
                $query->where('created_at','>=',$filterParameters['appliedDate']);
            });
//        $paginateBy = isset($filterParameters['records_per_page'])  ? $filterParameters['records_per_page'] : $paginateBy;
//        dd($candidates);
        $candidates= $candidates->latest()->paginate($paginateBy);
//        dd($candidates);
        return $candidates;
    }
}
