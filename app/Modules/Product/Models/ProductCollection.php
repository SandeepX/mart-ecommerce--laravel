<?php

namespace App\Modules\Product\Models;

use App\Modules\Application\Traits\IsActiveScope;
use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Application\Traits\QueryBuilderScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCollection extends Model
{
    use SoftDeletes, IsActiveScope, ModelCodeGenerator,QueryBuilderScope;

    protected $table = 'product_collections';
    protected $primaryKey = 'product_collection_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'product_collection_code',
        'product_collection_title',
        'product_collection_slug',
        'product_collection_subtitle',
        'product_collection_image',
        'remarks',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $hidden = [
        'backup_image'
    ];

    public $uploadFolder = 'uploads/product/product-collections/';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->product_collection_code = $model->generateProductCollectionCode();
            $model->backup_image = $model->product_collection_image;
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });

        static::updating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->backup_image = $model->product_collection_image;
            $model->updated_by = $authUserCode;
        });

        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
            $model->save();
        });
    }


    public function generateProductCollectionCode()
    {
        return $this->generateModelCode($this, $this->primaryKey, 'PCO-', '001', 3);
    }


    // product collection - verified,active products,untrashed
    public function products()
    {
        return $this->belongsToMany(ProductMaster::class,
         'product_collection_details',
         'product_collection_code',
         'product_code'
        )->withPivot([
            'is_active',
            'created_by',
            'deleted_at'
        ]);
    }

    public function limitedProducts()
    {
        return $this->products()->paginate(2);
    }

    public function activeProducts()
    {
        return $this->products()->wherePivot('is_active',1)
            ->qualifiedToDisplay();
    }
}
