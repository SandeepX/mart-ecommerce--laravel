<?php

namespace App\Modules\Types\Models;

use App\Modules\Application\Traits\IsActiveScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyType extends Model
{
    use SoftDeletes,IsActiveScope;
    protected $table = 'company_types';
    protected $primaryKey = 'company_type_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'company_type_code',
        'company_type_name',
        'slug',
        'is_active',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];


    public static function generateCompanyTypeCode()
    {
        $companyTypePrefix = 'CT';
        $initialIndex = '001';
        $companyType = self::withTrashed()->latest('id')->first();
        if($companyType){
            $codeTobePad = str_replace($companyTypePrefix,"",$companyType->company_type_code) +1 ;
            $paddedCode = str_pad($codeTobePad, 3, '0', STR_PAD_LEFT);
            $latestCompanyTypeCode = $companyTypePrefix.$paddedCode;
        }else{
            $latestCompanyTypeCode = $companyTypePrefix.$initialIndex;
        }
        return $latestCompanyTypeCode;
    }

    public static function getNationalCompanyTypeCode(){
        $nationalCompanyType= CompanyType::where('slug','national')->first();
        return $nationalCompanyType->company_type_code;
    }

}
