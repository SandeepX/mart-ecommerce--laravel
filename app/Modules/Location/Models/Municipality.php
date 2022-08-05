<?php

namespace App\Modules\Location\Models;


trait Municipality{

    public function district(){
        return $this->belongsTo(self::class, 'upper_location_code');
    }

    public function wards(){
        return $this->hasMany(self::class, 'upper_location_code');
    }
}