<?php
/**
 * Created by PhpStorm.
 * User: Shramik
 * Date: 02/18/2021
 */

namespace App\Modules\SalesManager\Resources;

use App\Modules\Store\Helpers\StoreMiscPaymentHelper;
use App\Modules\Store\Helpers\StoreTransactionHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreStatusDetailResource extends JsonResource
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
            'store_name' => $this->store_name,
            'store_code' => $this->store_code,
            'base_investment' => $this->storeTypePackage ? $this->storeTypePackage->base_investment : 'N/A',
            'non_refundable_registration_charge' => $this->storeTypePackage ? $this->storeTypePackage->non_refundable_registration_charge : 'N/A',
            'refundable_registration_charge' => $this->storeTypePackage ?  $this->storeTypePackage->refundable_registration_charge : 'N/A',
            'annual_purchasing_limit' => $this->storeTypePackage ? $this->storeTypePackage->annual_purchasing_limit : 'N/A',
            'has_purchase_power'=>$this->has_purchase_power,
        ];
        return $result;
//        if ($this->status === 'pending') {
//            $result['status_message'] = 'You account is still pending. This means that it will take some time to get your account accepted.';
//        } elseif($this->status === 'rejected') {
//            $result['status_message'] = 'Your Account has been rejected due to some reason. Please go through the reason and try again.Thank you! ';
//        }elseif ($this->status === 'approved'){
//            $result['has_paid_non_refundable_registration_charge']=1;
//            $result['has_paid_refundable_registration_charge']=1;
//            $result['status_message'] = 'Congratulations! Your account has been approved. You can now access all the features of dashboard, KYC, Balance and so on.';
//        }

//        if($this->status=='processing'){
//            if ($this->hasStorePaidInitialRegistrationCharge($this->store_code)) {
//                $lastPayment = StoreMiscPaymentHelper::getLatestMiscPaymentByVerificationStatusAndPaymentType(
//                    $this->store_code,
//                    'initial_registration'
//                );
//                $lastPaymentStatus = $lastPayment->verification_status ;
//                if($lastPaymentStatus== 'rejected'){
//                    $result['status_message'] ='
//                      Your last payment was rejected. Please pay again.
//                      Your non refundable registration fee is Rs.'.$this->storeTypePackage->non_refundable_registration_charge.'.
//                      Your refundable registration fee is Rs.'.$this->storeTypePackage->refundable_registration_charge.'
//                      Your base investment is Rs.'.$this->storeTypePackage->base_investment.'
//                    ';
//                    return $result;
//                }
//                if($initialRegistration < $this->storeTypePackage->non_refundable_registration_charge) {
//                    $result['has_paid_non_refundable_registration_charge']=0;
//                }else{
//                    $result['has_paid_non_refundable_registration_charge']=1;
//                }
//                if($refundableRegistration < $this->storeTypePackage->refundable_registration_charge)
//                {
//                    $result['has_paid_refundable_registration_charge']=0;
//                }else{
//                    $result['has_paid_refundable_registration_charge']=1;
//                }
//                $result['can_pay']= $this->has_purchase_power ;
//                //$result['payment_status']= 1;
//                $result['status_message']='Your amount has been received. Please wait until it is verified.';
//                $result['non_refundable_registration_charge'] = $this->storeTypePackage->non_refundable_registration_charge;
//                $result['refundable_registration_charge']=$this->storeTypePackage->refundable_registration_charge;
//                $result['annual_purchasing_limit']=$this->storeTypePackage->annual_purchasing_limit;
//                $result['base_investment']=$this->storeTypePackage->base_investment;
//            }else{
//                // $result['payment_status']=0;
//
//                if($initialRegistration < $this->storeTypePackage->non_refundable_registration_charge) {
//                    $result['has_paid_non_refundable_registration_charge']=0;
//                }else{
//                    $result['has_paid_non_refundable_registration_charge']=1;
//                }
//                if($refundableRegistration < $this->storeTypePackage->refundable_registration_charge)
//                {
//                    $result['has_paid_refundable_registration_charge']=0;
//                }else{
//                    $result['has_paid_refundable_registration_charge']=1;
//                }
//                //$result['can_pay']= $this->has_purchase_power;
//                $result['non_refundable_registration_charge'] = $this->storeTypePackage->non_refundable_registration_charge;
//                $result['status_message'] = 'Your account has been accepted. Please pay the non refundable and refundable registration charge to confirm your account approval. Your non refundable registration fee is Rs.'.$this->non_refundable_registration_charge.'
//                and refundable registration fee is Rs.'.$this->storeTypePackage->refundable_registration_charge;
//                $result['refundable_registration_charge']=$this->storeTypePackage->refundable_registration_charge;
//                $result['annual_purchasing_limit']=$this->storeTypePackage->annual_purchasing_limit;
//                $result['base_investment']=$this->storeTypePackage->base_investment;
//            }
//        }

    }

}
