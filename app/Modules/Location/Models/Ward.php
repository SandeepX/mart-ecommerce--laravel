<?php

namespace App\Modules\Location\Models;

trait Ward{

    public function municipality(){
      return $this->belongsTo(self::class, 'upper_location_code');
    }

    public function toles(){
       return $this->hasMany(self::class, 'upper_location_code');
    }
}