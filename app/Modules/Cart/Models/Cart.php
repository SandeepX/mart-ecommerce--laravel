<?php

namespace App\Modules\Cart\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Package\Models\PackageType;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Product\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes, ModelCodeGenerator;

    protected $table = 'carts';
    protected $primaryKey = 'cart_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'warehouse_code',
        'product_code',
        'product_variant_code',
        'package_code',
        'quantity'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->cart_code = $model->generateCode();
            $model->user_code = $authUserCode;
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });

        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
            $model->save();
        });
    }

    public function generateCode()
    {
        $prefix = 'C';
        $initialIndex = '1000';
        $cart= self::withTrashed()->latest('id')->first();
        if($cart){
            $codeTobePad = (int) (str_replace($prefix,"",$cart->cart_code) +1 );
            // $paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestCode = $prefix.$codeTobePad;
        }else{
            $latestCode = $prefix.$initialIndex;
        }
        return $latestCode;
    }

    public function product()
    {
        return $this->belongsTo(ProductMaster::class, 'product_code', 'product_code');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_code', 'product_variant_code');
    }

    public function productPackageType(){
        return $this->belongsTo(PackageType::class,
            'package_code','package_code');
    }


}
