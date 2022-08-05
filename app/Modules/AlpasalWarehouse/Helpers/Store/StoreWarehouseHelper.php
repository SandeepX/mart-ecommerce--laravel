<?php


namespace App\Modules\AlpasalWarehouse\Helpers\Store;


use App\Modules\Store\Models\Payments\StoreBalanceMaster;
use Illuminate\Support\Facades\DB;

class StoreWarehouseHelper
{

    public static function getStoresConnectedWithWarehouse($warehouseCode){

        return DB::table('store_warehouse')->where('warehouse_code',$warehouseCode)
            ->where('connection_status',1)
            ->pluck('store_code')->toArray();
    }

    public static function getConnectedWHStores($warehouseCode,$filterParameters){

       // dd($filterParameters);

        return  DB::table('store_warehouse')->where('store_warehouse.warehouse_code',$warehouseCode)

            ->join('stores_detail','stores_detail.store_code','=','store_warehouse.store_code')
            ->when($filterParameters['store_name'],function ($query) use ($filterParameters){
                $query->where('stores_detail.store_name', 'like', '%' . $filterParameters['store_name'] . '%');
            })
            ->when($filterParameters['store_owner_name'],function ($query) use ($filterParameters){
                $query->where('stores_detail.store_owner', 'like', '%' . $filterParameters['store_owner_name'] . '%');
            })

            ->where('store_warehouse.connection_status',1)
            ->select('store_warehouse.store_code',
                'store_warehouse.connection_status',
                'store_warehouse.created_at as connected_date',
                'stores_detail.store_name',
                'stores_detail.store_owner',
                'stores_detail.store_contact_phone',
                'stores_detail.store_contact_mobile',
                'stores_detail.store_email'
            )->paginate(20);

    }
}
