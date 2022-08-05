<?php

namespace App\Modules\Product\Models;

use App\Modules\Category\Models\CategoryMaster;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    public function product(){
        return $this->belongsTo(ProductMaster::class,'product_code');
    }

    public function category(){
        return $this->belongsTo(CategoryMaster::class,'category_code');
    }
}