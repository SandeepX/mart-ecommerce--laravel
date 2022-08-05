<?php


namespace App\Modules\Store\Models\PreOrder;


use App\Modules\AlpasalWarehouse\Models\PreOrder\StorePreOrderDispatchDetail;
use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderListing;
use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderProduct;
use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\Store\Models\Store;
use App\Modules\Wallet\Models\WalletTransactionPurpose;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorePreOrder extends Model
{
    use SoftDeletes;

    protected $table = 'store_preorder';
    protected $primaryKey = 'store_preorder_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'warehouse_preorder_listing_code',
        'store_code',
        'payment_status',
        'status',
        'has_merged',
        'early_finalized',
        'early_cancelled',
        'created_by',
        'updated_by'
    ];
    const VAT_PERCENTAGE_VALUE = 13;

    const STATUSES=['pending','finalized','processing','dispatched','cancelled','ready_to_dispatch'];

    public function generateCode()
    {
        $prefix = 'SPO';
        $initialIndex = '1000';
        $preOrder = self::withTrashed()->latest('id')->first();
        if($preOrder){
            $codeTobePad = (int) (str_replace($prefix,"",$preOrder->store_preorder_code) +1 );
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
            $model->store_preorder_code = $model->generateCode();
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });

       /* static::updating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->updated_by = $authUserCode;
        });*/

        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
            $model->save();
        });
    }

    public function store(){
        return $this->belongsTo(Store::class,'store_code','store_code');
    }

    public function warehousePreOrderListing(){
        return $this->belongsTo(WarehousePreOrderListing::class,'warehouse_preorder_listing_code','warehouse_preorder_listing_code');
    }

    public function storePreOrderDetails(){
        return $this->hasMany(StorePreOrderDetail::class,'store_preorder_code','store_preorder_code');
    }

    public function storePreOrderView(){
        return $this->hasOne(StorePreOrderView::class,'store_preorder_code','store_preorder_code');
    }

    public function storePreOrderStatusLogs(){
        return $this->hasMany(StorePreOrderStatusLog::class,'store_preorder_code','store_preorder_code');
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by','user_code');
    }
    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by','user_code');
    }

    public function scopeValidPreOrder($query){
        return $query->whereHas('warehousePreOrderListing',function ($q){
            $q->where('status_type','!=','cancelled');
        });
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

    public function storePreOrderDispatchDetail()
    {
        return $this->hasOne(StorePreOrderDispatchDetail::class, 'store_preorder_code', 'store_preorder_code');
    }

    public function storePreOrderEarlyFinalization(){
        return $this->hasOne(StorePreOrderEarlyFinalization::class,'store_preorder_code','store_preorder_code');
    }

    public function storePreOrderEarlyCancelled(){
        return $this->hasOne(StorePreorderEarlyCancellation::class,'store_preorder_code','store_preorder_code');
    }

}
