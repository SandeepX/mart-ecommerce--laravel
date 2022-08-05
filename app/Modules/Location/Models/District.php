<?php

namespace App\Modules\Location\Models;


trait District{

    public function province(){
       return $this->belongsTo(self::class, 'upper_location_code');
    }

    public function municipalities(){
       return $this->hasMany(self::class, 'upper_location_code');
    }
}