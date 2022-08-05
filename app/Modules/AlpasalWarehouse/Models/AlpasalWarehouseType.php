<?php

namespace App\Modules\AlpasalWarehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlpasalWarehouseType extends Model
{
    use SoftDeletes;
    protected $table = 'alpasal_warehouse_types';
    protected $primaryKey = 'warehouse_type_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'warehouse_type_name',
        'slug',
        'remarks',
        'warehouse_type_key',
        'is_closed',
        'created_by',
        'updated_by',
    ];
}
