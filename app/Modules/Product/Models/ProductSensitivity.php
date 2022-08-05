<?php

namespace App\Modules\Product\Models;

use App\Modules\Application\Traits\CheckDelete\CheckDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSensitivity extends Model
{
    use SoftDeletes,CheckDelete;
    protected $primaryKey = 'sensitivity_code';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [ 
        'sensitivity_code', 
        'sensitivity_name',
        'slug',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function generateSensitivityCode()
    {
        $sensitivityPrefix = 'PS';
        $initialIndex = '0001';
        $sensitivity = self::withTrashed()->latest('created_at')->first();
        if($sensitivity){
            $codeTobePad = str_replace($sensitivityPrefix,"",$sensitivity->sensitivity_code) +1 ;
            $paddedCode = str_pad($codeTobePad, 4, '0', STR_PAD_LEFT);
            $latestSensitivityCode = $sensitivityPrefix.$paddedCode;
        }else{
            $latestSensitivityCode = $sensitivityPrefix.$initialIndex;
        }
        return $latestSensitivityCode;
    }


    public function products(){
        return $this->hasMany(ProductMaster::class,'sensitivity_code');
    }
}
