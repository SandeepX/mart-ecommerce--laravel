<?php


namespace App\Modules\SalesManager\Resources\SIMManagerAttendance;

use Illuminate\Http\Resources\Json\JsonResource;

class SIMManagerAttendanceResource extends JsonResource
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
            'msmi_attendance_code' => $this->msmi_attendance_code,
            'attendance_date' => $this->attendance_date,
            'status' => $this->status,
            'remarks' => $this->remarks,
        ];
    }
}





