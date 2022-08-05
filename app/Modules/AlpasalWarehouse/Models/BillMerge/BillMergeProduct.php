<?php


namespace App\Modules\AlpasalWarehouse\Models\BillMerge;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductVariant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BillMergeProduct extends Model
{
    use ModelCodeGenerator;

    protected $table = 'bill_merge_product';
    protected $primaryKey = 'bill_merge_product_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'bill_merge_master_code',
        'bill_merge_details_code',
        'product_code',
        'product_variant_code',
        'package_code',
        'product_packaging_history_code',
        'initial_order_quantity',
        'quantity',
        'is_taxable',
        'unit_rate',
        'subtotal',
        'status',
    ];


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->bill_merge_product_code = $model->generateBillMergeProductCode();
        });
    }
    public function generateBillMergeProductCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'BMP', '1000', false);
    }
    public function product()
    {
        return $this->belongsTo(ProductMaster::class,'product_code','product_code');
    }
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class,'product_variant_code','product_variant_code');
    }
    public function billMergeDetail(){
        return $this->belongsTo(BillMergeDetail::class,'bill_merge_details_code','bill_merge_details_code');
    }
}
