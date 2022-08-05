<?php

namespace App\Modules\Types\Models;

use App\Modules\Application\Traits\IsActiveScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreSize extends Model
{
    use SoftDeletes,IsActiveScope;

    protected $table = 'store_sizes';
    protected $primaryKey = 'store_size_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'store_size_code',
        'store_size_name',
        'slug',
        'is_active',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function generateStoreSizeCode()
    {
        $storeSizePrefix = 'SSZ';
        $initialIndex = '001';
        $storeSize = self::withTrashed()->latest('id')->first();
        if($storeSize){
            $codeTobePad = str_replace($storeSizePrefix,"",$storeSize->store_size_code) +1 ;
            $paddedCode = str_pad($codeTobePad, 3, '0', STR_PAD_LEFT);
            $latestStoreSizeCode = $storeSizePrefix.$paddedCode;
        }else{
            $latestStoreSizeCode = $storeSizePrefix.$initialIndex;
        }
        return $latestStoreSizeCode;
    }
    public static function getSmallSizedStoreTypeCode(){
        $smallSizeStore= StoreSize::where('slug','small-sized')->first();
        return $smallSizeStore->store_size_code;
    }
}
