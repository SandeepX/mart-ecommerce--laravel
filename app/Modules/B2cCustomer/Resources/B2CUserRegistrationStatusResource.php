<?php
/**
 * Created by PhpStorm.
 * User: Sandeep
 * Date: 06/18/2021
 */

namespace App\Modules\B2cCustomer\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class B2CUserRegistrationStatusResource extends JsonResource
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
            $result['status_message'] = 'You account is still pending. This means that it will take some time to get your account accepted.';
        }
        if ($this->status === 'processing') {
            $result['status_message'] = 'You account is Processing. This means your we are verifing your given data.';
        } elseif ($this->status === 'rejected') {
            $result['status_message'] = 'Your Account has been rejected due to some reason. Please go through the reason and try again.Thank you! ';
        } elseif ($this->status === 'approved') {
            $result['status_message'] = 'Congratulations! Your account has been registered.Now to access all the features of dashboard, Balance and so on,plz verify your login phone number.';
        }

        return $result;
    }

}

