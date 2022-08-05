<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 8/1/2021
 * Time: 5:20 PM
 */

namespace App\Modules\ContentManagement\Resources;

use App\Modules\ContentManagement\Models\TeamGallery;
use Illuminate\Http\Resources\Json\JsonResource;


class TeamGalleryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'image' => (isset($this->image))?photoToUrl($this->image,url(TeamGallery::TEAM_GALLERY_PATH)): NULL,
            'description' =>(isset($this->description))?$this->description:NULL,

        ];
    }
}
