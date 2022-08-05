<?php

namespace App\Modules\Store\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreDocument extends Model
{   
    use SoftDeletes;
    protected $table = 'store_documents';
    protected $fillable = [
        'document_name',
        'document_file',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
