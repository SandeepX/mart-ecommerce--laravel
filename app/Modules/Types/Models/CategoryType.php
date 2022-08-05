<?php

namespace App\Modules\Types\Models;

use App\Modules\Application\Traits\CheckDelete\CheckDelete;
use App\Modules\Category\Models\CategoryMaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryType extends Model
{
    use SoftDeletes,CheckDelete;
    protected $table = 'category_types';
    protected $primaryKey = 'category_type_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'category_type_code',
        'category_type_name',
        'slug',
        'is_active',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];


    public function generateCategoryTypeCode()
    {
        $categoryTypePrefix = 'CTC';
        $initialIndex = '001';
        $categoryType = self::withTrashed()->latest('id')->first();
        if($categoryType){
            $codeTobePad = str_replace($categoryTypePrefix,"",$categoryType->category_type_code) +1 ;
            $paddedCode = str_pad($codeTobePad, 3, '0', STR_PAD_LEFT);
            $latestcategoryTypeCode = $categoryTypePrefix.$paddedCode;
        }else{
            $latestcategoryTypeCode = $categoryTypePrefix.$initialIndex;
        }
        return $latestcategoryTypeCode;
    }


    public function categories()
    {
      return $this->belongsToMany(CategoryMaster::class,'category_category_type', 'category_type_code', 'category_code');
    }
}
