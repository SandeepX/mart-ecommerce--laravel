<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 8/1/2021
 * Time: 5:20 PM
 */

namespace App\Modules\ContentManagement\Resources;

use App\Modules\ContentManagement\Models\Vision;
use Illuminate\Http\Resources\Json\JsonResource;


class VisionMissionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'page_image' => (isset($this->page_image))?photoToUrl($this->page_image,url(Vision::PAGE_IMAGE_PATH)):NULL,
            'vision_description' =>(isset($this->vision_description))?$this->vision_description:NULL,
            'mission_description'=>(isset($this->mission_description))?$this->mission_description:NULL,
        ];
    }
    public function with($request)
    {
        return [
            'error' => false,
            'code' => 200
        ];
    }
}
