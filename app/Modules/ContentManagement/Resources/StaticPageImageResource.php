<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 8/1/2021
 * Time: 5:20 PM
 */

namespace App\Modules\ContentManagement\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\ContentManagement\Models\StaticPageImage;

class StaticPageImageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'static_page_image_code' => $this->static_page_image_code,
            'image' => url('uploads/content-management/static-page-images/' . $this->image),
            'created_at' =>date_format($this->created_at,"Y/m/d"),
        ];
    }
}










