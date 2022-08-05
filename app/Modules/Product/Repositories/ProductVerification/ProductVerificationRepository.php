<?php

namespace App\Modules\Product\Repositories\ProductVerification;

use App\Modules\Product\Models\ProductVerification;
use App\Modules\Product\Models\ProductVerificationDetail;

class ProductVerificationRepository
{
    public function storeProductVerification($validatedProductVerification, $product)
    {
        ProductVerification::updateOrCreate(
            [
                'product_code' => $product->product_code
            ],
            [
            'user_code' => getAuthUserCode(),
            'verification_status' => $validatedProductVerification['verification_status'],
            'verification_date' => date('Y-m-d')
            ]
        );

        //Insert Into Product Verification Details
        $latestVerificationDetail = $product->verification->verificationDetails()->latest()->first();
        ProductVerificationDetail::create([
            'verification_code' => $product->verification->verification_code,
            'old_verification_status' => isset($latestVerificationDetail) ? $latestVerificationDetail->new_verification_status : null,
            'new_verification_status' => $product->verification->verification_status,
            'old_verification_date' => isset($latestVerificationDetail) ? $latestVerificationDetail->new_verification_date : null,
            'new_verification_date' => $product->verification->verification_date,
            'remarks' => $validatedProductVerification['remarks']
        ]);

    }
}
