<?php

namespace App\Modules\Types\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CancellationParam extends Model
{
    use SoftDeletes;
    protected $table = 'cancellation_para';
    protected $primaryKey = 'cancellation_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'cancellation_code',
        'cancellation_name',
        'slug',
        'is_active',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];


    public function generateCancellationParamCode()
    {
        $cancellationParamPrefix = 'CP';
        $initialIndex = '001';
        $cancellationParam = self::withTrashed()->latest('id')->first();
        if($cancellationParam){
            $codeTobePad = str_replace($cancellationParamPrefix,"",$cancellationParam->cancellation_code) +1 ;
            $paddedCode = str_pad($codeTobePad, 3, '0', STR_PAD_LEFT);
            $latestcancellationParamCode = $cancellationParamPrefix.$paddedCode;
        }else{
            $latestcancellationParamCode = $cancellationParamPrefix.$initialIndex;
        }
        return $latestcancellationParamCode;
    }
}
