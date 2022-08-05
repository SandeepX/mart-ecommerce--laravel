<?php

namespace App\Modules\Types\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RejectionParam extends Model
{
    use SoftDeletes;
    protected $table = 'rejection_para';
    protected $primaryKey = 'rejection_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'rejection_code',
        'rejection_name',
        'slug',
        'is_active',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];


    public function generateRejectionParamCode()
    {
        $rejectionParamPrefix = 'RP';
        $initialIndex = '001';
        $rejectionParam = self::withTrashed()->latest('id')->first();
        if($rejectionParam){
            $codeTobePad = str_replace($rejectionParamPrefix,"",$rejectionParam->rejection_code) +1 ;
            $paddedCode = str_pad($codeTobePad, 3, '0', STR_PAD_LEFT);
            $latestrejectionParamCode = $rejectionParamPrefix.$paddedCode;
        }else{
            $latestrejectionParamCode = $rejectionParamPrefix.$initialIndex;
        }
        return $latestrejectionParamCode;
    }
}
