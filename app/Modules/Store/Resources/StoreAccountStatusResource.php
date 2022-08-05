<?php
/**
 * Created by PhpStorm.
 * User: Shramik
 * Date: 02/18/2021
 */

namespace App\Modules\Store\Resources;

use App\Modules\Store\Classes\StoreBalance;

use App\Modules\Store\Helpers\StoreMiscPaymentHelper;
use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Repositories\Payment\StoreMiscellaneousPaymentRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreAccountStatusResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */

    public function toArray($request)
    {
        $store = $this->resource;
        $initialRegistration = (new StoreBalance())->getNonRefundableRegistrationChargeDeducted($store);
        $refundableRegistration = (new StoreBalance())->getRefundableRegistrationChargeDeducted($store);


        $result = [
            'status' => $this->status,
            'remarks' => $this->when($this->status === "rejected", $this->remarks),
        ];
        if ($this->status === 'pending') {
            $result['can_pay'] = 0;
            $result['status_message'] = 'You account is still pending.';
        } elseif ($this->status === 'rejected') {
            $result['status_message'] = 'Your account has been rejected due to some reason.';
            $result['can_pay'] = 0;
        } elseif ($this->status === 'approved') {
            $result['can_pay'] = 0;
            $result['status_message'] = 'Congratulations ! Your account has been approved.';

            if ($storeTypePackage = $this->storeTypePackage) {
                $storeBaseInvestmentCharge = $storeTypePackage->base_investment;
                if($store->has_purchase_power == 0){
                    $result['status_message'] .= 'Please Pay Base Investment Charge ('.$storeBaseInvestmentCharge.') for purchase power ';
                }
            }
            $result['can_pay'] = !$this->has_purchase_power;
            $result['has_purchase_power'] = $this->has_purchase_power;
        }

        if ($this->status == 'processing') {
            if ($storeTypePackage = $this->storeTypePackage) {
                $storeNonRefundableRegCharge = $storeTypePackage->non_refundable_registration_charge;
                $storeRefundableRegCharge = $storeTypePackage->refundable_registration_charge;
                $storeBaseInvestmentCharge = $storeTypePackage->base_investment;
                $annualPurchasingLimit = $storeTypePackage->annual_purchasing_limit;
                $storePurchasePower = $store->has_purchase_power;

                $result['has_paid_non_refundable_registration_charge'] = ($initialRegistration >= $storeNonRefundableRegCharge) ? 1 : 0;
                $result['has_paid_refundable_registration_charge'] = ($refundableRegistration >= $storeRefundableRegCharge) ? 1 : 0;

                $statusMessage = 'Your account has been accepted.';
                if(!$result['has_paid_non_refundable_registration_charge'] || !$result['has_paid_refundable_registration_charge']){
                    $statusMessage .= 'Please pay';
                    if(!$result['has_paid_non_refundable_registration_charge']){
                        $statusMessage .= ' registration charge ,';
                    }
                    if(!$result['has_paid_refundable_registration_charge']){
                        $statusMessage .= ' refundable registration charge ,';
                    }
                    $statusMessage = rtrim($statusMessage, ',');
                    $statusMessage .= 'to get the account approved .';
                }
//                if($store->has_purchase_power == 0){
//                    $result['enable_purchase_message'] = 'Please Pay Base Investment Charge ('.$storeBaseInvestmentCharge.') for purchase power';
//                }

                if($store->has_purchase_power == 0){
                    $statusMessage .= ' Please Pay Base Investment Charge ('.$storeBaseInvestmentCharge.') for purchase power ';
                }

                $result['can_pay'] = $storePurchasePower;
                $result['has_purchase_power'] = $this->has_purchase_power;
                $result['non_refundable_registration_charge'] = $storeNonRefundableRegCharge;
                $result['status_message'] = $statusMessage;
                $result['refundable_registration_charge'] = $storeRefundableRegCharge;
                $result['base_investment'] = $storeBaseInvestmentCharge;
                $result['annual_purchasing_limit'] = $annualPurchasingLimit;
            } else {
                $result['message'] = "There is no Package Related To The Store: $this->store_name";
            }
        }
        return $result;

    }

}
