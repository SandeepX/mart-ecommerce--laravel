<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/11/2020
 * Time: 3:01 PM
 */

namespace App\Modules\User\Models;


use App\Modules\AlpasalWarehouse\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;

class WarehouseUser extends Model
{

    protected $table = 'warehouse_user';

    protected $fillable = [
        'warehouse_code','user_code'
    ];

    /**
     * Get the user that owns the WarehouseUser.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_code', 'user_code')->withDefault();
    }

    /**
     * Get the warehouse that owns the WarehouseUser.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_code', 'warehouse_code')->withDefault();
    }
}