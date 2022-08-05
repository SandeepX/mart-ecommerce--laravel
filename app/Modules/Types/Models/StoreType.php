<?php


namespace App\Modules\Types\Models;

use App\Modules\Application\Traits\IsActiveScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreType extends Model
{
    use SoftDeletes;
    use IsActiveScope;

    protected $table = 'store_types';
    protected $primaryKey = 'store_type_code';
    public $incrementing = false;
    protected $keyType = 'string';
    const IMAGE_PATH='uploads/storetypes/images/';

    protected $fillable = [
        'id',
        'store_type_code',
        'store_type_name',
        'store_type_slug',
        'sort_order',
        'image',
        'description',
        'is_active',
        'created_by',
    ];


    public static function generateStoreTypeCode()
    {
        $storeTypePrefix = 'SST';
        $initialIndex = '001';
        $storeType = self::withTrashed()->latest('id')->first();
        if ($storeType) {
            $codeTobePad = str_replace($storeTypePrefix, "", $storeType->store_type_code) + 1;
            $paddedCode = str_pad($codeTobePad, 3, '0', STR_PAD_LEFT);
            $latestStoreTypeCode = $storeTypePrefix . $paddedCode;
        } else {
            $latestStoreTypeCode = $storeTypePrefix . $initialIndex;
        }
        return $latestStoreTypeCode;
    }

}
