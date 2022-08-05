<?php

namespace App\Modules\ManagerDiary\Helpers;

use App\Modules\ManagerDiary\Models\Diary\ManagerDiary;

class ManagerDiaryFilter
{

    public static function filterPaginatedManagerDiary($filterParameters,$paginateBy=10,$with=[]){

        $amountCondition=isset($filterParameters['amount_condition']) && in_array($filterParameters['amount_condition'],['>','<', '>=','<=','='])? true:false;
        $referredByOperator = (isset($filterParameters['is_referred']) && $filterParameters['is_referred']=='yes') ? '!=' : ((isset($filterParameters['is_referred']) && $filterParameters['is_referred']=='no' ) ? '=' : NULL);
       // dd($referredByOperator);
        $managerDiaries = ManagerDiary::with($with)
                           ->when(isset($filterParameters['manager_code']),function ($query) use ($filterParameters){
                                 $query->where('manager_code' ,$filterParameters['manager_code']);
                           })
                            ->when(isset($filterParameters['store_name']),function ($query) use ($filterParameters){
                                 $query->where('store_name','like','%'.$filterParameters['store_name'] . '%');
                            })
                            ->when($referredByOperator && isset($filterParameters['is_referred']),function ($query) use ($filterParameters,$referredByOperator){
                                 $query->where('referred_store_code',$referredByOperator,NULL);
                            })
                            ->when(isset($filterParameters['owner_name']),function ($query) use ($filterParameters){
                                $query->where('owner_name','like','%'.$filterParameters['owner_name'] . '%');
                            })
                            ->when(isset($filterParameters['phone_no']),function ($query) use ($filterParameters){
                                $query->where(function ($query) use ($filterParameters){
                                    $query->where('phone_no',$filterParameters['phone_no'])
                                        ->orWhere('alt_phone_no',$filterParameters['phone_no']);
                                });
                            })
                            ->when($amountCondition && isset($filterParameters['amount']),function ($query) use($filterParameters){
                                 $query->where('business_investment_amount',$filterParameters['amount_condition'],$filterParameters['amount']);
                            })
                            ->when(isset($filterParameters['amount_from']),function ($query) use ($filterParameters){
                                $query->where('business_investment_amount' ,'>=' ,$filterParameters['amount_from']);
                            })
                            ->when(isset($filterParameters['amount_to']),function ($query) use ($filterParameters){
                                $query->where('business_investment_amount','<=',$filterParameters['amount_to']);
                            })
                            ->when(isset($filterParameters['date_from']),function ($query) use($filterParameters){
                                 $query->whereDate('created_at','>=',date('y-m-d',strtotime($filterParameters['date_from'])));
                            })
                            ->when(isset($filterParameters['date_to']),function ($query) use($filterParameters){
                                 $query->whereDate('created_at','<=',date('y-m-d',strtotime($filterParameters['date_to'])));
                            })
                            ->when($filterParameters['province_code'], function ($query) use ($filterParameters) {
                                 $query->whereHas('ward.municipality.district.province', function ($query) use ($filterParameters) {
                                      $query->where('location_code', $filterParameters['province_code']);
                                 });
                            })
                            ->when($filterParameters['district_code'], function ($query) use ($filterParameters) {
                                 $query->whereHas('ward.municipality.district', function ($query) use ($filterParameters) {
                                      $query->where('location_code', $filterParameters['district_code']);
                                 });
                            })
                            ->when($filterParameters['municipality_code'], function ($query) use ($filterParameters) {
                                 $query->whereHas('ward.municipality', function ($query) use ($filterParameters) {
                                      $query->where('location_code', $filterParameters['municipality_code']);
                                 });
                            })
                            ->when($filterParameters['ward_code'], function ($query) use ($filterParameters) {
                                 $query->whereHas('ward', function ($query) use ($filterParameters) {
                                      $query->where('location_code', $filterParameters['ward_code']);
                                 });
                            });

                        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;
                        $managerDiaries = $managerDiaries->latest()->paginate($paginateBy);
                        return $managerDiaries;

    }

}
