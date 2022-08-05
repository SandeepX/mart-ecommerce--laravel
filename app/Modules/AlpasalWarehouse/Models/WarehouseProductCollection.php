<?php


namespace App\Modules\AlpasalWarehouse\Models;


use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Product\Models\ProductVariant;
use App\Modules\User\Models\User;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseProductCollection extends Model
{
    use SoftDeletes,ModelCodeGenerator;
    protected $table = 'wh_product_collections';
    protected $primaryKey = 'product_collection_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'product_collection_code',
        'warehouse_code',
        'product_collection_title',
        'product_collection_slug',
        'product_collection_subtitle',
        'product_collection_image',
        'remarks',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public $uploadFolder = 'uploads/alpasalwarehouse/warehouse-product-collections/';

    protected $hidden = [
        'backup_image'
    ];

    public function generateCode()
    {

        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'WPC', '1000', true);

//        $prefix = 'WPC';
//        $initialIndex = '1000';
//        $warehouseProductCollection = self::latest('id')->first();
//
//        if($warehouseProductCollection){
//            $codeTobePad = (int) (str_replace($prefix,"",$warehouseProductCollection->product_collection_code) +1 );
//            //$paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
//            $latestCode = $prefix.$codeTobePad;
//        }else{
//            $latestCode = $prefix.$initialIndex;
//        }
//        return $latestCode;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->product_collection_code = $model->generateCode();
            $model->backup_image = $model->product_collection_image;
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });

        static::updating(function ($model) {

            $authUserCode = getAuthUserCode();
            $model->backup_image = $model->product_collection_image;
            $model->updated_by = $authUserCode;

        });
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_code','warehouse_code');
    }

    // product collection - verified,active products,untrashed
    public function warehouseProductMasters()
    {
        return $this->belongsToMany(WarehouseProductMaster::class,
            'wh_product_collection_details',
            'product_collection_code',
            'warehouse_product_master_code'
        )->withPivot([
            'is_active',
            'created_by',
            'deleted_at'
        ]);
    }
    public function qualifiedWarehouseProductMasters()
    {
        return $this->belongsToMany(WarehouseProductMaster::class,
            'wh_product_collection_details',
            'product_collection_code',
            'warehouse_product_master_code'
        )->distinct('warehouse_product_master_code')->whereHas('product',function ($query){
            $query->where('is_active',1)
                  ->whereHas('unitPackagingDetails');
        })->qualifiedToDisplay()
            ->withPivot([
            'is_active',
            'created_by',
            'deleted_at'
        ]);
    }


    public function limitedProducts()
    {
        return $this->warehouseProductMasters()->paginate(2);
    }
    public function verified()
    {

    }
    public function activeWarehouseProducts()
    {
        return $this->warehouseProductMasters()->where('warehouse_products_master.is_active',1);
    }
}

