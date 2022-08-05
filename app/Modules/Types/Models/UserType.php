<?php

namespace App\Modules\Types\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserType extends Model
{
    use SoftDeletes;

    protected $table = 'user_types';
    protected $primaryKey = 'user_type_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_type_code',
        'user_type_name',
        'slug',
        'is_active',
        'remarks'
    ];



    public static function generateUserTypeCode()
    {
        $userTypePrefix = 'UT';
        $initialIndex = '001';
        $userType = self::withTrashed()->latest('id')->first();
        if($userType){
            $codeTobePad = str_replace($userTypePrefix,"",$userType->user_type_code) +1 ;
            $paddedCode = str_pad($codeTobePad, 3, '0', STR_PAD_LEFT);
            $latestUserTypeCode = $userTypePrefix.$paddedCode;
        }else{
            $latestUserTypeCode = $userTypePrefix.$initialIndex;
        }
        return $latestUserTypeCode;
    }

}
