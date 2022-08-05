<?php

namespace App\Modules\Types\Models;

use App\Modules\Application\Traits\IsActiveScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrationType extends Model
{
    use SoftDeletes,IsActiveScope;
    protected $table = 'registration_types';
    protected $primaryKey = 'registration_type_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'registration_type_code',
        'registration_type_name',
        'slug',
        'is_active',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];


    public static function generateRegistrationTypeCode()
    {
        $registrationTypePrefix = 'RT';
        $initialIndex = '001';
        $registrationType = self::withTrashed()->latest('id')->first();
        if($registrationType){
            $codeTobePad = str_replace($registrationTypePrefix,"",$registrationType->registration_type_code) +1 ;
            $paddedCode = str_pad($codeTobePad, 3, '0', STR_PAD_LEFT);
            $latestRegistrationTypeCode = $registrationTypePrefix.$paddedCode;
        }else{
            $latestRegistrationTypeCode = $registrationTypePrefix.$initialIndex;
        }
        return $latestRegistrationTypeCode;
    }

    public static function getRegistrarRegistrationTypeCode(){
        $registrarRegistrationType= RegistrationType::where('slug','registrar')->first();
        return $registrarRegistrationType->registration_type_code;
    }
}
