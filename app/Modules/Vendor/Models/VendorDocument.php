<?php

namespace App\Modules\Vendor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorDocument extends Model
{   
    use SoftDeletes;
    protected $table = 'vendor_documents';
    protected $fillable = [
        'document_name',
        'document_file',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
