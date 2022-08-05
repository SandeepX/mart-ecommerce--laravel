<?php

namespace App\Modules\SystemSetting\Models;


use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    protected $table = 'seo_settings';
    protected $fillable = [
        'meta_title',
        'meta_description',
        'keywords',
        'revisit_after',
        'author',
        'sitemap_link',
        'updated_by',
    ];
}