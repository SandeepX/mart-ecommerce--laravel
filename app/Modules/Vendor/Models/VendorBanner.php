<?php

namespace App\Modules\Vendor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorBanner extends Model
{
    use SoftDeletes;
    protected $table = 'vendor_banners';
    protected $fillable = [
        'vendor_code',
        'banner',
        'created_by',
        'updated_by'
    ];
}
