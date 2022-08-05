<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/22/2020
 * Time: 4:14 PM
 */

namespace App\Modules\Store\Helpers;


use App\Modules\Location\Models\LocationHierarchy;
use App\Modules\Store\Models\Payments\StoreBalanceMaster;
use App\Modules\Store\Models\Store;
use Illuminate\Support\Facades\DB;

class StoreFilter
{

    public static function filterPaginatedStores($filterParameters,$paginateBy,$with=[])
    {

        $stores = Store::with($with)
            ->select(
                'stores_detail.*'
            )
            ->where('status','approved')
            ->when($filterParameters['store_name'], function ($query) use ($filterParameters) {
                $query->where('store_name', 'like', '%' . $filterParameters['store_name'] . '%');
            })->when($filterParameters['store_owner'], function ($query) use ($filterParameters) {
                $query->where('store_owner', 'like', '%' . $filterParameters['store_owner'] . '%');
            })->when($filterParameters['registration_type'], function ($query) use ($filterParameters) {
                $query->where('store_registration_type_code', $filterParameters['registration_type']);
            })->when($filterParameters['store_status'], function ($query) use ($filterParameters) {
                $query->where('status', $filterParameters['store_status']);
            })->when($filterParameters['company_type'], function ($query) use ($filterParameters) {
                $query->where('store_company_type_code', $filterParameters['company_type']);
            })
            ->when($filterParameters['store_contact_no'], function ($query) use ($filterParameters) {
                $query->where(function ($query) use ($filterParameters) {
                    $query->where('store_contact_mobile', 'like', '%' . $filterParameters['store_contact_no'] . '%')
                        ->orWhere('store_contact_phone','like','%'.$filterParameters['store_contact_no']);
                });
            })
            ->when($filterParameters['store_pan_vat_no'], function ($query) use ($filterParameters) {
                $query->where('pan_vat_no', 'like', '%' . $filterParameters['store_pan_vat_no'] . '%');
            })
            ->when($filterParameters['joined_date_from'], function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '>=', date('y-m-d', strtotime($filterParameters['joined_date_from'])));
            })->when($filterParameters['joined_date_to'], function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '<=', date('y-m-d', strtotime($filterParameters['joined_date_to'])));
            })->when($filterParameters['province'], function ($query) use ($filterParameters) {

                $query->whereHas('location.municipality.district.province', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['province']);
                });
            })->when($filterParameters['district'], function ($query) use ($filterParameters) {

                $query->whereHas('location.municipality.district', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['district']);
                });
            })->when($filterParameters['municipality'], function ($query) use ($filterParameters) {

                $query->whereHas('location.municipality', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['municipality']);
                });
            })->when($filterParameters['ward'], function ($query) use ($filterParameters) {

                $query->whereHas('location', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['ward']);
                });
            });
        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;
        $stores = $stores->latest()->paginate($paginateBy);
        return $stores;
    }


    public static function filterPaginatedPendingRegistrationStores($filterParameters,$paginateBy,$with=[])
    {
        $stores = Store::with($with)
            ->select(
                'stores_detail.*'
            )
            ->where('status','!=','approved')
            ->when($filterParameters['store_name'], function ($query) use ($filterParameters) {
                $query->where('store_name', 'like', '%' . $filterParameters['store_name'] . '%');
            })->when($filterParameters['store_owner'], function ($query) use ($filterParameters) {
                $query->where('store_owner', 'like', '%' . $filterParameters['store_owner'] . '%');
            })
            ->when($filterParameters['registration_type'], function ($query) use ($filterParameters) {
                $query->where('store_registration_type_code', $filterParameters['registration_type']);
            })
            ->when($filterParameters['store_status'], function ($query) use ($filterParameters) {
                $query->where('status', $filterParameters['store_status']);
            })
            ->when($filterParameters['company_type'], function ($query) use ($filterParameters) {
                $query->where('store_company_type_code', $filterParameters['company_type']);
            })
            ->when($filterParameters['store_contact_no'], function ($query) use ($filterParameters) {
                $query->where(function ($query) use ($filterParameters) {
                    $query->where('store_contact_mobile', 'like', '%' . $filterParameters['store_contact_no'] . '%')
                        ->orWhere('store_contact_phone','like','%'.$filterParameters['store_contact_no']);
                });
            })
            ->when($filterParameters['store_pan_vat_no'], function ($query) use ($filterParameters) {
                $query->where('pan_vat_no', 'like', '%' . $filterParameters['store_pan_vat_no'] . '%');
            })
            ->when($filterParameters['joined_date_from'], function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '>=', date('y-m-d', strtotime($filterParameters['joined_date_from'])));
            })
            ->when($filterParameters['joined_date_to'], function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '<=', date('y-m-d', strtotime($filterParameters['joined_date_to'])));
            })
            ->when($filterParameters['province'], function ($query) use ($filterParameters) {

                $query->whereHas('location.municipality.district.province', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['province']);
                });
            })->when($filterParameters['district'], function ($query) use ($filterParameters) {

                $query->whereHas('location.municipality.district', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['district']);
                });
            })->when($filterParameters['municipality'], function ($query) use ($filterParameters) {

                $query->whereHas('location.municipality', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['municipality']);
                });
            })->when($filterParameters['ward'], function ($query) use ($filterParameters) {

                $query->whereHas('location', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['ward']);
                });
            });

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $stores = $stores->latest()->paginate($paginateBy);
        return $stores;
    }

    public static function getStoreContactNumber($storeCode)
    {
        $contactNumber = DB::table('stores_detail')
            ->where('store_code','S1000')
            ->select('store_contact_mobile')
            ->first('store_contact_mobile');
        return $contactNumber;
    }
}
