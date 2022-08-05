<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/29/2020
 * Time: 1:26 PM
 */

namespace App\Modules\Store\Helpers;


use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPaymentView;
use Illuminate\Support\Facades\DB;

class StoreMiscPaymentHelper
{

    //used in admin panel
//    public static function filterStoreMiscPaymentByParameters(array $filterParameters, $with = [])
//    {
//        $storeMiscPayments = StoreMiscellaneousPayment::select(
//            'store_miscellaneous_payments.id',
//            'store_miscellaneous_payments.store_code',
//            'store_miscellaneous_payments.payment_for',
//            'store_miscellaneous_payments.created_at'
//        )
//            ->join('stores_detail',
//                'stores_detail.store_code', '=',
//                'store_miscellaneous_payments.store_code'
//            )
    // ->addSelect(DB::raw(" store_miscellaneous_payments.deposited_by as honysingh"))
//            ->addSelect('stores_detail.store_name')
//            ->addSelect([
//                'last_payment_date' => StoreMiscellaneousPayment::select(
//                    'store_miscellaneous_payments.created_at'
//                )
//                    ->where('store_miscellaneous_payments.payment_for','load_balance')
//                    ->where('store_miscellaneous_payments.store_code','stores_detail.store_code')
//                    ->orderByDesc('store_miscellaneous_payments.id')
//                    ->limit(1)
//            ])
//            ->addSelect([
//                'last_payment_amount' => StoreMiscellaneousPayment::select('amount')
//                    ->orderByDesc('id')
//                    ->limit(1)
//            ])
//            ->selectRaw("(
//                 SELECT amount from store_miscellaneous_payments
//
//
//                 limit 1
//            )
//            as last_payment_amount"
//            )
//            ->addSelect([
//                'last_verification_status' => StoreMiscellaneousPayment::select('verification_status')
//                    ->orderByDesc('id')
//                    ->limit(1)
//            ])
//            ->addSelect([
//                'last_payment_type' => StoreMiscellaneousPayment::select('payment_type')
//                    ->orderByDesc('id')
//                    ->limit(1)
//            ])
//           ->addSelect(DB::raw('SUM(amount) as total_deposited_amount'))
//
//            ->groupBy(
//                'store_miscellaneous_payments.payment_for',
//                'store_miscellaneous_payments.store_code'
//            )
//            ->orderBy('store_miscellaneous_payments.id','DESC')
//            ->paginate(10);

//        $verificationStatus = in_array($filterParameters['payment_status'], StoreMiscellaneousPayment::VERIFICATION_STATUSES);
//
//        $miscPayments = StoreMiscellaneousPayment::with($with)->when($filterParameters['store_code'], function ($query) use ($filterParameters) {
//            $query->where('store_code', $filterParameters['store_code']);
//        })->when($verificationStatus, function ($query) use ($filterParameters) {
//            $query->where('verification_status', $filterParameters['payment_status']);
//        })->latest()->get();

//        return $storeMiscPayments;
//    }


    public static function filterStoreMiscPaymentByParameters(array $filterParameters, $with = [])
    {

        $amountCondition = (isset($filterParameters['amount_condition']) && in_array($filterParameters['amount_condition'], ['>', '<', '>=', '<=', '='])) ? true : false;


        $storeMiscPayments = StoreMiscellaneousPaymentView::select(
            'store_miscellaneous_payments_view.store_code',
            'store_miscellaneous_payments_view.payment_for',
            'store_miscellaneous_payments_view.Pending',
            'store_miscellaneous_payments_view.Verified',
            'store_miscellaneous_payments_view.Rejected',
            'store_miscellaneous_payments_view.lastStatus',
            'store_miscellaneous_payments_view.lastPaymentDate'
        )
            ->join('stores_detail',
                'stores_detail.store_code', '=',
                'store_miscellaneous_payments_view.store_code'
            )
            ->addSelect('stores_detail.store_name')
            ->when(isset($filterParameters['store_name']), function ($query) use ($filterParameters) {
                $query->whereHas('store', function ($query) use ($filterParameters) {
                    $query->where('store_name', 'like', '%' . $filterParameters['store_name'] . '%');
                });
            })
            ->groupBy(
                'store_miscellaneous_payments_view.payment_for',
                'store_miscellaneous_payments_view.store_code'
            )
            ->when(isset($filterParameters['payment_for']),function($query) use ($filterParameters){
                $query->where('payment_for',$filterParameters['payment_for']);
            })
            ->when(isset($filterParameters['last_verification_status']),function($query) use ($filterParameters){
                $query->where('lastStatus',$filterParameters['last_verification_status']);
            })
            ->where(function ($query) use ($filterParameters,$amountCondition) {
                if($amountCondition && isset($filterParameters['amount'])) {
                    $query->where('Pending', $filterParameters['amount_condition'], $filterParameters['amount'])
                    ->orWhere('Verified', $filterParameters['amount_condition'], $filterParameters['amount'])
                    ->orWhere('Rejected', $filterParameters['amount_condition'], $filterParameters['amount']);
                }
            })
            ->orderBy('store_miscellaneous_payments_view.lastPaymentDate','DESC')
            ->paginate(15);

       // dd($storeMiscPayments);

        return $storeMiscPayments;
    }



    //used in api
    public static function filterPaginatedStoreMiscPaymentByParameters(array $filterParameters, $paginateBy, $with = [])
    {

        $verificationStatus = (isset($filterParameters['verification_status']) && in_array($filterParameters['verification_status'], StoreMiscellaneousPayment::VERIFICATION_STATUSES)) ? true : false;
        $paymentType = (isset($filterParameters['payment_type']) && in_array($filterParameters['payment_type'], StoreMiscellaneousPayment::PAYMENT_TYPE)) ? true : false;
        $amountCondition = (isset($filterParameters['amount_condition']) && in_array($filterParameters['amount_condition'], ['>', '<', '>=', '<=', '='])) ? true : false;

        $miscPayments = StoreMiscellaneousPayment::with($with)
            ->when(isset($filterParameters['store_code']), function ($query) use ($filterParameters) {
                $query->where('store_code', $filterParameters['store_code']);
            })->when(isset($filterParameters['store_name']), function ($query) use ($filterParameters) {
                $query->whereHas('store', function ($query) use ($filterParameters) {
                    $query->where('store_name', 'like', '%' . $filterParameters['store_name'] . '%');
                });
            })->when(isset($filterParameters['misc_payment_code']), function ($query) use ($filterParameters) {
                $query->where('store_misc_payment_code', 'like', '%' . $filterParameters['store_misc_payment_code'] . '%');
            })->when($verificationStatus, function ($query) use ($filterParameters) {
                $query->where('verification_status', $filterParameters['verification_status']);
            })->when($paymentType, function ($query) use ($filterParameters) {
                $query->where('payment_type', $filterParameters['payment_type']);
            })->when(isset($filterParameters['payment_date_from']), function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '>=', date('y-m-d', strtotime($filterParameters['payment_date_from'])));
            })->when(isset($filterParameters['payment_date_to']), function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '<=', date('y-m-d', strtotime($filterParameters['payment_date_to'])));
            })->when($amountCondition && isset($filterParameters['amount']), function ($query) use ($filterParameters) {
                $query->where('amount', $filterParameters['amount_condition'], $filterParameters['amount']);
            });

        //for global search
        $miscPayments = $miscPayments->when(isset($filterParameters['global_search_keyword']), function ($q) use ($filterParameters) {

            $q->where(function ($query) use ($filterParameters) {
                $query->where('store_misc_payment_code', 'like', '%' . $filterParameters['global_search_keyword'] . '%')
                    ->orWhere('verification_status', 'like', '%' . $filterParameters['global_search_keyword'] . '%')
                    ->orWhere('payment_type', 'like', '%' . $filterParameters['global_search_keyword'] . '%')
                    ->orWhereDate('created_at', date('y-m-d', strtotime($filterParameters['global_search_keyword'])))
                    ->orWhere('amount', 'like', '%' . $filterParameters['global_search_keyword'] . '%');
            });
        });

        $paginateBy = isset($filterParameters['records_per_page'])  ? $filterParameters['records_per_page'] : $paginateBy;

        $miscPayments = $miscPayments->latest()->paginate($paginateBy);
        return $miscPayments;
    }


    public static function filterPaginatedStoreMiscPaymentByParametersUsingGroupBy(array $filterParameters,$paginateBy,$with=[]){

        $verificationStatus = (isset($filterParameters['verification_status']) && in_array($filterParameters['verification_status'],StoreMiscellaneousPayment::VERIFICATION_STATUSES)) ? true :false;
        $paymentType = (isset($filterParameters['payment_type']) && in_array($filterParameters['payment_type'],StoreMiscellaneousPayment::PAYMENT_TYPE)) ? true :false;
        $amountCondition=(isset($filterParameters['amount_condition']) && in_array($filterParameters['amount_condition'],['>','<', '>=','<=','='])) ? true :false;

        if(is_null($filterParameters['payment_date_from']) && is_null($filterParameters['payment_date_to'])){

            $miscPayments = StoreMiscellaneousPayment::with($with)
                ->selectRaw('store_misc_payment_code , user_code , store_code, payment_for, sum(amount) as amount, max(created_at) as created_at')
                ->groupBy(['user_code','payment_for'])
                ->orderBy('created_at','DESC')
                ->paginate($paginateBy);
        }else{

            $miscPayments = StoreMiscellaneousPayment::with($with)
                ->selectRaw('store_misc_payment_code , 	user_code , store_code, payment_for, sum(amount) as amount, max(created_at) as created_at')
                ->groupBy(['user_code','payment_for'])
                ->orderBy('created_at','DESC')

                ->when(isset($filterParameters['payment_date_from']),function ($query) use($filterParameters){
                    $query->whereDate('created_at','>=',date('y-m-d',strtotime($filterParameters['payment_date_from'])))
                        ->selectRaw('store_misc_payment_code , user_code , store_code, payment_for, sum(amount) as amount, max(created_at) as created_at')
                        ->groupBy(['user_code','payment_for'])
                        ->orderBy('created_at','DESC');
                })

                ->when(isset($filterParameters['payment_date_to']),function ($query) use($filterParameters){
                    $query->whereDate('created_at','<=',date('y-m-d',strtotime($filterParameters['payment_date_to'])))
                        ->selectRaw('store_misc_payment_code , user_code , store_code, payment_for, sum(amount) as amount, max(created_at) as created_at')
                        ->groupBy(['user_code','payment_for'])
                        ->orderBy('created_at','DESC');
                });


            $paginateBy = isset($filterParameters['records_per_page'])  ? $filterParameters['records_per_page'] : $paginateBy;

            $miscPayments= $miscPayments->latest()->paginate($paginateBy);
            // dd($miscPayments);


        }
        // dd($miscPayments);

        return $miscPayments;
    }

    public static function filterAllMiscPaymentsByStoreCodeAndPaymentType(array $filterParameters,$storeCode,$paymentFor,$paginationBy,$with=[]){

        //dd($filterParameters,$storeCode,$paymentFor,$with);
        $paymentType = (isset($filterParameters['payment_type']) && in_array($filterParameters['payment_type'],StoreMiscellaneousPayment::PAYMENT_TYPE)) ? true :false;
        $amountCondition = (isset($filterParameters['amount_condition']) && in_array($filterParameters['amount_condition'], ['>', '<', '>=', '<=', '='])) ? true : false;
        $paymentStatus = (isset($filterParameters['payment_status']) && in_array($filterParameters['payment_status'], StoreMiscellaneousPayment::VERIFICATION_STATUSES)) ? true : false;


        $miscPayments=  StoreMiscellaneousPayment::with($with)
            ->where('store_code',$storeCode)
            ->where('payment_for',$paymentFor)
            ->when(isset($filterParameters['payment_code']),function ($query) use ($filterParameters){
                $query->where('store_misc_payment_code',$filterParameters['payment_code']);
            })
            ->when($paymentType,function ($query) use ($filterParameters){
                $query->where('payment_type',$filterParameters['payment_type']);
            })
            ->when($amountCondition && isset($filterParameters['amount']), function ($query) use ($filterParameters) {
                $query->where('amount', $filterParameters['amount_condition'], $filterParameters['amount']);
            })
            ->when(isset($filterParameters['payment_date_from']), function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '>=', date('y-m-d', strtotime($filterParameters['payment_date_from'])));
            })
            ->when(isset($filterParameters['payment_date_to']), function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '<=', date('y-m-d', strtotime($filterParameters['payment_date_to'])));
            })
            ->when($paymentStatus,function ($query) use ($filterParameters){
                $query->where('verification_status',$filterParameters['payment_status']);
            })
            ->when(isset($filterParameters['has_matched']),function ($query) use ($filterParameters){
                $query->where('has_matched',$filterParameters['has_matched']);
            })
            ->latest()
            ->paginate($paginationBy);

        return $miscPayments;

    }

    public static function getLatestMiscPaymentByVerificationStatusAndPaymentType($storeCode,$paymentFor){
        $storeInitialRegPayment = StoreMiscellaneousPayment::where('store_code',$storeCode)
            ->where('payment_for',$paymentFor)
            ->latest()
            ->first();
        return $storeInitialRegPayment;
    }





}
