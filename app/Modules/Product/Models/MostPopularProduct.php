<?php

namespace App\Modules\Product\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MostPopularProduct extends Model
{
    use ModelCodeGenerator;
    protected $table = 'most_popular_products';

    protected $fillable = [
        'warehouse_code',
        'product_code',
        'total_amount'
    ];

    public static function boot()
    {
        parent::boot();
        static::updating(function ($model) {
            $model->created_at = Carbon::now();
            $model->updated_at = Carbon::now();
        });
        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

}
