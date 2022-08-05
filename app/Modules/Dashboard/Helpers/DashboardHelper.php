<?php


namespace App\Modules\Dashboard\Helpers;


use App\Modules\AlpasalWarehouse\Models\PurchaseOrderDetail;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductStockView;
use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseOrder;
use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseOrderReceivedDetail;
use App\Modules\Store\Models\Balance\StoreCurrentBalance;
use App\Modules\Store\Models\StoreOrder;
use App\Modules\Store\Models\StoreOrderDetails;

class DashboardHelper
{

    public static function getTotalSalesAmount(){
        return StoreOrder::sum('acceptable_amount');
    }

    public static function getTotalPurchaseAmount(){
        return WarehousePurchaseOrder::where('status','!=','draft')->sum('total_amount');
    }

    public static function getTotalStoresBalance(){
        return StoreCurrentBalance::sum('balance');
    }

    public static function getWarehousesTotalProductStock(){
        return WarehouseProductMaster::sum('current_stock');
    }

    public static function getTotalNumberOfSalesQuantity(){
        return StoreOrderDetails::where('acceptance_status','accepted')->sum('quantity');
    }

    public static function getTotalNumberOfPurchaseQuantity(){
        return WarehousePurchaseOrderReceivedDetail::sum('received_quantity');
    }

    public static function getTotalPendingSalesOrders(){
        return StoreOrder::where('delivery_status','pending')->count();
    }
    public static function getTotalPendingPurchaseOrders(){
        return WarehousePurchaseOrder::where('status','processing')->count();
    }

}
