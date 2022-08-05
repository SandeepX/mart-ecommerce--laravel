<?php

namespace App\Modules\Location\Models;


trait Province{

    public function country(){
       return $this->belongsTo(self::class, 'upper_location_code');
    }

    public function districts(){
       return $this->hasMany(self::class, 'upper_location_code');
    }
}