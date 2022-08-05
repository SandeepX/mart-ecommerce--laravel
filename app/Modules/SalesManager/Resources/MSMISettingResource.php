<?php


namespace App\Modules\SalesManager\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MSMISettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'msmi_settings_code' => $this->msmi_settings_code,
            'salary' => $this->salary,
            'terms_and_condition' => $this->terms_and_condition,
        ];
    }
}


