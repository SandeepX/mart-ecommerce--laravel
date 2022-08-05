<?php

namespace App\Modules\AlpasalWarehouse\Models;

use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderListing;
use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseStockTransfer;
use App\Modules\Location\Models\LocationHierarchy;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\WarehouseUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;
    protected $table = 'warehouses';
    protected $primaryKey = 'warehouse_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'warehouse_name',
        'warehouse_type_code',
        'slug',
        'location_code',
        'remarks',
        'landmark_name',
        'latitude',
        'longitude',
        'pan_vat_type',
        'pan_vat_no',
        'warehouse_logo',
        'contact_name',
        'contact_email',
        'contact_phone_1',
        'contact_phone_2'
    ];

    const IMAGE_PATH='uploads/warehouses/logo/';

    const  VAT_PERCENTAGE_VALUE = 13;

    public function generateWarehouseCode()
    {
        $warehousePrefix = 'AW';
        $initialIndex = '1000';
        $warehouse = self::withTrashed()->latest('id')->first();
        if($warehouse){
            $codeTobePad = (int) (str_replace($warehousePrefix,"",$warehouse->warehouse_code) +1 );
            //$paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestWarehouseCode = $warehousePrefix.$codeTobePad;
        }else{
            $latestWarehouseCode = $warehousePrefix.$initialIndex;
        }
        return $latestWarehouseCode;
    }

    public function location(){
        return $this->belongsTo(LocationHierarchy::class, 'location_code');
    }

    public function warehouseType(){
        return $this->belongsTo(AlpasalWarehouseType::class, 'warehouse_type_code');
    }

    public function isOpenWarehouseType(){

        if ($this->warehouseType->slug == 'open'){
            return true;
        }
        return false;
    }

    public function isClosedWarehouseType(){

        if ($this->warehouseType->slug == 'closed'){
            return true;
        }
        return false;
    }

    public function stores(){
        return $this->belongsToMany(Store::class, 'store_warehouse', 'warehouse_code', 'store_code')
            ->withPivot('connection_status')->withTimestamps();
    }

    public function warehouseUsers()
    {
        return $this->hasMany(WarehouseUser::class,'warehouse_code','warehouse_code');
    }

    public function warehouseProductMaster()
    {
        return $this->hasMany(WarehouseProductMaster::class,'warehouse_code','warehouse_code');
    }

    public function warehouseAdmins(){
        return $this->warehouseUsers()->whereHas('user.userType',function ($query){
            return $query->where('slug','warehouse-admin');
        });
    }
    public function getLogoUploadPath(){

        return self::IMAGE_PATH;
    }
    public function warehousePreOrderListings()
    {
       return $this->hasMany(WarehousePreOrderListing::class,'warehouse_code','warehouse_code');
    }

    public function engagedStockTransfers()
    {
        return $this->hasMany(WarehouseStockTransfer::class, 'source_warehouse_code', 'warehouse_code');
    }

    public function stockTransfersBeingSource()
    {
        return $this->engagedStockTransfers()->where('source_warehouse_code', $this->warehouse_code);
    }
    public function stockTransfersBeingDestination()
    {
        return $this->engagedStockTransfers()->where('destination_warehouse_code', $this->warehouse_code);
    }
}
