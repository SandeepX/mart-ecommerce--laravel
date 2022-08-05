<?php

namespace App\Modules\Location\Models;


trait Tole{

    public function ward(){
        return $this->belongsTo(self::class, 'upper_location_code');
    }
}