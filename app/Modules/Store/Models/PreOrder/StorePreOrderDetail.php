<?php


namespace App\Modules\Store\Models\PreOrder;


use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderProduct;
use App\Modules\Package\Models\PackageType;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorePreOrderDetail extends Model
{
    use SoftDeletes;

    protected $table = 'store_preorder_details';
    protected $primaryKey = 'store_preorder_detail_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_preorder_code',
        'warehouse_preorder_product_code',
        'package_code',
        'product_packaging_history_code',
        'quantity',
        'initial_order_quantity',
        'is_taxable',
        'delivery_status',
        'created_by',
        'updated_by',
        'admin_updated_by',
        'admin_updated_at'
    ];

    public function generateCode()
    {
        $prefix = 'SPOD';
        $initialIndex = '1000';
        $preOrderDetail = self::withTrashed()->latest('id')->first();
        if($preOrderDetail){
            $codeTobePad = (int) (str_replace($prefix,"",$preOrderDetail->store_preorder_detail_code) +1 );
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
            $model->store_preorder_detail_code = $model->generateCode();
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });

      /*  static::updating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->updated_by = $authUserCode;
        });*/

        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
            $model->save();
        });
    }

    public function warehousePreOrderProduct(){
        return $this->belongsTo(WarehousePreOrderProduct::class,'warehouse_preorder_product_code','warehouse_preorder_product_code');
    }

    public function storePreOrder(){
        return $this->belongsTo(StorePreOrder::class,'store_preorder_code','store_preorder_code');
    }

    public function storePreOrderDetailView(){
        return $this->hasOne(StorePreOrderDetailView::class,'store_preorder_detail_code','store_preorder_detail_code');
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by','user_code');
    }
    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by','user_code');
    }

    public function productPackageType(){
        return $this->belongsTo(PackageType::class,
            'package_code','package_code');
    }
}
