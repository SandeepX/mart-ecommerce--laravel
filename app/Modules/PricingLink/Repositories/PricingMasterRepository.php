<?php


namespace App\Modules\PricingLink\Repositories;

use App\Modules\PricingLink\Models\PricingMaster;


class PricingMasterRepository
{
    public function getAllPricingLinks($paginatedBy=10)
    {
        return PricingMaster::orderBy('id','desc')->paginate($paginatedBy);
    }

    public function getPricingLinkByLinkCode($linkCode)
    {
        return PricingMaster::where('link_Code',$linkCode)->first();
    }

    public function storePricingLink($validatedData)
    {
        return PricingMaster::create($validatedData);
    }

    public function findPricingLinkByCode($pricingMasterCode)
    {
        return PricingMaster::where('pricing_master_code',$pricingMasterCode)->first();
    }

    public function updatePricingMaster($validatedData,$pricingLink)
    {
        return $pricingLink->update($validatedData);
    }

    public function changePricingLinkStatus($pricingLink)
    {
        return $pricingLink->update([
            'is_active' => !$pricingLink['is_active']
        ]);
    }
}
