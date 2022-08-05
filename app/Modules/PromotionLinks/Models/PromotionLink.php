<?php

namespace App\Modules\PromotionLinks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotionLink extends Model
{
    use SoftDeletes;
    protected $table = 'promotion_links';
    protected $fillable = [
        'filename',
        'file',
        'link_code',
        'title',
        'description',
        'image',
        'og_title',
        'og_description',
        'og_image',
    ];
    const OG_IMAGE_PATH = 'uploads/promotion-links/og-image/';
    const PROMOTION_FILE_PATH = 'uploads/promotion-links/';
    const IMAGE_PATH = 'uploads/promotion-links/image';
    const PAGINATE_BY = 20;

    public function getPromotionFileUploadPath(){
        return self::PROMOTION_FILE_PATH;
    }

    public function getPromotionImageUploadPath(){
        return self::IMAGE_PATH;
    }

    public function getOGImageUploadPath(){
        return self::OG_IMAGE_PATH;
    }

}
