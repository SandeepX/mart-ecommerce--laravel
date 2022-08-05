<?php

namespace App\Modules\Application\Traits;

trait IsActiveScope
{

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeNotActive($query)
    {
        return $query->where('is_active', 0);
    }
}
