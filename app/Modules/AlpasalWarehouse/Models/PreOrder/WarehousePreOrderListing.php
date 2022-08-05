<?php


namespace App\Modules\AlpasalWarehouse\Models\PreOrder;


use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\Application\Traits\IsActiveScope;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\Store\Models\PreOrder\StorePreOrderDetail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehousePreOrderListing extends Model
{

    use SoftDeletes,IsActiveScope;

    protected $table = 'warehouse_preorder_listings';
    protected $primaryKey = 'warehouse_preorder_listing_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'warehouse_code',
        'pre_order_name',
        'start_time',
        'end_time',
        'finalization_time',
        'is_active',
        'banner_image',
        'is_finalized',
        'status_type',
        'remarks',
        'created_by',
        'updated_by',
        'longitude',
    ];

    protected $hidden = [
        'backup_image'
    ];

    const IMAGE_PATH='uploads/pre_orders/banner_image/';

    public function generateCode()
    {
        $prefix = 'WPLC';
        $initialIndex = '1000';
        $preOrder = self::withTrashed()->latest('id')->first();
        if($preOrder){
            $codeTobePad = (int) (str_replace($prefix,"",$preOrder->warehouse_preorder_listing_code) +1 );
            //$paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestCode = $prefix.$codeTobePad;
        }else{
            $latestCode = $prefix.$initialIndex;
        }
        return $latestCode;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->warehouse_preorder_listing_code = $model->generateCode();
            $model->backup_image = $model->banner_image;
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });

        static::updating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->backup_image = $model->banner_image;
            $model->updated_by = $authUserCode;
        });

        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
            $model->save();
        });
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_code','warehouse_code');
    }

    public function warehousePreOrderProducts(){
        return $this->hasMany(WarehousePreOrderProduct::class,'warehouse_preorder_listing_code','warehouse_preorder_listing_code');
    }

    public function storePreOrders(){
        return $this->hasMany(StorePreOrder::class,'warehouse_preorder_listing_code','warehouse_preorder_listing_code');
    }

    public function warehousePreOrderPurchaseOrders(){
        return $this->hasMany(WarehousePreOrderPurchaseOrder::class, 'warehouse_preorder_listing_code','warehouse_preorder_listing_code');
    }

    public function scopeDisplayable($query)
    {
        return $query
            //->where('start_time', '<=', Carbon::now('Asia/Kathmandu')->toDateTimeString())
            ->where('finalization_time', '>=', Carbon::now('Asia/Kathmandu')->toDateTimeString())
            ->where('is_active',1)
            ->where('status_type','!=','cancelled');
    }

    public function scopeFinalizable($query)
    {
        return $query->where('finalization_time', '<', Carbon::now('Asia/Kathmandu')->toDateTimeString())
            ->where('is_finalized',0)->where('status_type','!=','cancelled');
    }

    public function scopePreOrderable($query)
    {
        return $query
            ->where('start_time', '<=', Carbon::now('Asia/Kathmandu')->toDateTimeString())
            ->where('end_time', '>=', Carbon::now('Asia/Kathmandu')->toDateTimeString())
            ->where('status_type','!=','cancelled');
    }

    public function isPastFinalizationTime(){
        $currentTime=Carbon::now('Asia/Kathmandu')->toDateTimeString();
        if ($currentTime > $this->finalization_time){
            return true;
        }
        return false;
    }
    public function isPastEndTime(){
        $currentTime=Carbon::now('Asia/Kathmandu')->toDateTimeString();
        if ($currentTime > $this->end_time){
            return true;
        }
        return false;
    }


    public function isPastStartTime(){
        $currentTime=Carbon::now('Asia/Kathmandu')->toDateTimeString();
        if ($currentTime > $this->start_time){
            return true;
        }
        return false;
    }


    public function hasBeenOrderedByStore(){
        return count($this->storePreOrders) > 0? true : false;
    }

    public function getStartTime($dateFormat='Y-m-d H:i:s'){
        return date($dateFormat,strtotime($this->start_time));
    }
    public function getEndTime($dateFormat='Y-m-d H:i:s'){
        return date($dateFormat,strtotime($this->end_time));
    }
    //html format Y-m-d\TH:i
    public function getFinalizationTime($dateFormat='Y-m-d H:i:s'){
        return date($dateFormat,strtotime($this->finalization_time));
    }

    public function isPreOrderable(){
        $currentTimeString=Carbon::now( 'Asia/Kathmandu')->toDateTimeString();

        if ($this->start_time <= $currentTimeString &&
            $this->end_time >= $currentTimeString
        ){
            return true;
        }
        return  false;
    }

    public function isFinalized(){

        if ($this->is_finalized == 1){
            return true;
        }

        return false;
    }

    public function isCancelled(){

        if ($this->status_type == 'cancelled'){
            return true;
        }

        return false;
    }
    public function isDisplayable(){
            $currentTimeString=Carbon::now( 'Asia/Kathmandu')->toDateTimeString();

            if ($this->start_time <= $currentTimeString &&
               // $this->end_time >= $currentTimeString &&
                $currentTimeString <= $this->finalization_time
            ){
                return true;
            }
        return  false;
    }

    public function getBannerUploadPath(){

        return self::IMAGE_PATH;
    }


}
