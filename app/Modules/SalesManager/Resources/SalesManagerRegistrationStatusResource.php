<?php
/**
 * Created by PhpStorm.
 * User: Shramik
 * Date: 02/18/2021
 */

namespace App\Modules\SalesManager\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SalesManagerRegistrationStatusResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'status' => $this->status,
            'remarks' => $this->when($this->status === "rejected", $this->remarks),
        ];
        if ($this->status === 'pending') {
            $result['status_message'] = 'You account is still pending.It might take up to 24 hours to get your account accepted.';
        }if ($this->status === 'processing') {
        $result['status_message'] = 'You account is Processing. We are verifying your given data.';
       }
        elseif($this->status === 'rejected') {
            $result['status_message'] = 'Your Account has been rejected due to some reason. Please go through the reason and try again.Thank you! ';
        }elseif ($this->status === 'approved'){
            $result['status_message'] = 'Congratulations! Your account has been approved.';
        }

        return $result;
    }

}
