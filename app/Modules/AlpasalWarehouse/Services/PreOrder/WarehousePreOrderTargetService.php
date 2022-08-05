<?php


namespace App\Modules\AlpasalWarehouse\Services\PreOrder;


use App\Modules\AlpasalWarehouse\Repositories\PreOrder\WarehousePreOrderTargetRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehousePreOrderTargetService
{
    private $warehousePreOrderTargetRepository;


    public function __construct(WarehousePreOrderTargetRepository $warehousePreOrderTargetRepository)
    {
        $this->warehousePreOrderTargetRepository=$warehousePreOrderTargetRepository;
    }

     public function getStoreTypes()
     {
         return $this->warehousePreOrderTargetRepository->getStoreTypes();
     }

    public function storeWarehousePreOrderTarget($preOrderListingCode,$validated)
    {
        $data=[];
        for ($i=0; $i<count($validated['store_type_code']); $i++)
        {
            $group=[
                'warehouse_preorder_listing_code'=>$preOrderListingCode,
                'store_type_code'=>$validated['store_type_code'][$i],
                'target_type'=>'group',
                'target_value'=>(int)$validated['target_group_value'][$i],
            ];

            array_push($data,$group);

            $individual=[
                'warehouse_preorder_listing_code'=>$preOrderListingCode,
                'store_type_code'=>$validated['store_type_code'][$i],
                'target_type'=>'individual',
                'target_value'=>(int)$validated['target_individual_value'][$i],
            ];

            array_push($data,$individual);
        }

        foreach($data as $key=>$value) {
            $this->warehousePreOrderTargetRepository->storeWarehousePreOrderTarget($value);
        }
    }
    public function getPreOrderTargetsOfPreOrderListing($preOrderListingCode)
    {
        $preOrderTargets= $this->warehousePreOrderTargetRepository->getPreOrderTargetsOfPreOrderListing($preOrderListingCode);
        $groupedPreOrderTargets=$preOrderTargets->groupBy('store_type_name');

        return $groupedPreOrderTargets;
    }

    public function getStoreTypeTargets($preOrderListingCode)
    {
        return $this->warehousePreOrderTargetRepository->getStoreTypeTargets($preOrderListingCode);
    }
}
