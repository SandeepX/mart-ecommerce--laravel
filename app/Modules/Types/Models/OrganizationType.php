<?php

namespace App\Modules\Types\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationType extends Model
{
    protected $table = 'organization_types';
    protected $fillable = ['type_name','slug','is_active'];
}
