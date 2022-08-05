<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;


    /**
     * @param $query
     * @return mixed
     */
    public function scopeEnabled($query)
    {
        return $query->where('status', self::STATUS_ENABLED);
    }


    /**
     * @param $query
     * @return mixed
     */
    public function scopeDisabled($query)
    {
        return $query->where('status', self::STATUS_ENABLED);
    }

}

