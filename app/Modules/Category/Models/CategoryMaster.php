<?php

namespace App\Modules\Category\Models;

use App\Modules\Application\Traits\CheckDelete\CheckDelete;
use App\Modules\Brand\Models\Brand;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Types\Models\CategoryType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryMaster extends Model
{
    use SoftDeletes, CheckDelete;

    protected $table = 'category_master';

    protected $primaryKey = 'category_code';
    protected $keyType = 'string';
    public $incrementing =  false;
    protected $fillable = [
        'category_code',
        'category_name',
        'slug',
        'upper_category_code',
        'category_banner',
        'category_image',
        'category_icon',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
            'category_type'
    ];

    const BANNER_UPLOAD_PATH = 'uploads/categories/banners/';
    const CATEGORY_IMAGE_UPLOAD_PATH = 'uploads/categories/images/';
    const CATEGORY_ICON_UPLOAD_PATH = 'uploads/categories/icons/';

    public $defaultNotFoundImage = 'default/images/alplogo.png';

    public static function generateCategoryCode()
    {
        $categoryPrefix = 'C';
        $initialIndex = '1000';
        $category = self::withTrashed()->latest('id')->first();
        if($category){
            $codeTobePad = (int) (str_replace($categoryPrefix,"",$category->category_code) +1) ;
            //$paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestCategoryCode = $categoryPrefix.$codeTobePad;
        }else{
            $latestCategoryCode = $categoryPrefix.$initialIndex;
        }
        return $latestCategoryCode;
    }

    public function lowerCategories(){
        return $this->hasMany(self::class, 'upper_category_code');
    }

    public function upperCategory(){
        return $this->belongsTo(self::class, 'upper_category_code');
    }

    public function hasChildren(){
        return count($this->lowerCategories) ? true : false;
    }

    public function brands(){
        return $this->belongsToMany(Brand::class, 'category_brand', 'category_code', 'brand_code');
    }

    public function products(){
        return $this->belongsToMany(ProductMaster::class, 'product_category', 'category_code', 'product_code');
    }


//    public function descendants()
//    {
//        return $this->lowerCategories()->with('descendants');
//    }

    public function checkRelation(){
        return $this->canDelete('lowerCategories');
    }

    public function categoryTypes(){
        return $this->belongsToMany(CategoryType::class, 'category_category_type', 'category_code', 'category_type_code');
    }

    public function getCategoryImage()
    {
        $categoryImage = asset($this->defaultNotFoundImage);
        if($this->category_image){
            $imagePath = photoToUrl($this->category_image, asset(self::CATEGORY_IMAGE_UPLOAD_PATH));
            if(checkIfFileExists($this->category_image,self::CATEGORY_IMAGE_UPLOAD_PATH)){
              $categoryImage =  $imagePath;
            }
        }
        return $categoryImage;
    }

    public function getCategoryIcon()
    {
        $categoryIcon = asset($this->defaultNotFoundImage);
        if($this->category_icon){
            $imagePath = photoToUrl($this->category_icon, asset(self::CATEGORY_ICON_UPLOAD_PATH));
            if(checkIfFileExists($this->category_icon,self::CATEGORY_ICON_UPLOAD_PATH)){
                $categoryIcon =  $imagePath;
            }
        }
        return $categoryIcon;
    }



}
