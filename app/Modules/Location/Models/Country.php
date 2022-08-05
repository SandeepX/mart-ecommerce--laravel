<?php

namespace App\Modules\Location\Models;
 
trait Country{

    public function provinces(){
        return $this->hasMany(self::class, 'upper_location_code'); 
    }
}

    