<?php


namespace App\Modules\AlpasalWarehouse\Services\PreOrder;
use App\Modules\Store\Helpers\PreOrder\StorePreOrderHelper;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderEarlyCancellationRepository;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderRepository;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderStatusLogRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;


class WarehouseStorePreOrderEarlyCancelService
{
    private $storePreOrderRepository;
    private $storePreOrderEarlyCancellationRepository;
    private $storePreOrderStatusLogRepository;

    public function __construct(
        StorePreOrderRepository $storePreOrderRepository,
        StorePreOrderEarlyCancellationRepository $storePreOrderEarlyCancellationRepository,
        StorePreOrderStatusLogRepository $storePreOrderStatusLogRepository

    )
    {
        $this->storePreOrderRepository = $storePreOrderRepository;
        $this->storePreOrderEarlyCancellationRepository = $storePreOrderEarlyCancellationRepository;
        $this->storePreOrderStatusLogRepository = $storePreOrderStatusLogRepository;
    }

    public function createStorePreOrderEarlyCancel($storePreOrderCode)
    {
        try{
            $with = ['warehousePreOrderListing','store:store_code,store_name'];
            $storePreOrder = $this->storePreOrderRepository
                ->getStorePreOrderByPreOrderCode($storePreOrderCode,$with);

            if($storePreOrder->status != 'pending' || $storePreOrder->early_finalized || $storePreOrder->early_cancelled){
                throw new Exception('Early Cancellation cannot be done. This Order is already in ' .$storePreOrder['status']. ' state!');
            }

            return $storePreOrder;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function saveStorePreOrderEarlyCancel($storePreOrderCode,$validatedData)
    {
        try{
            $with = ['warehousePreOrderListing'];
            $storePreOrder = $this->storePreOrderRepository->getStorePreOrderByPreOrderCode($storePreOrderCode,$with);

            if($storePreOrder->status != 'pending'){
                throw new Exception('Early Cancellation cannot be done. This Order is already in ' .$storePreOrder['status']. ' state!');
            }

            $validatedData['status'] = 'cancelled';

            $earlyCancelValidatedData = [];
            $earlyCancelValidatedData['store_preorder_code'] = $storePreOrderCode;
            $earlyCancelValidatedData['early_cancelled_date'] = Carbon::now()->toDateTimeString();
            $earlyCancelValidatedData['early_cancelled_remarks'] = $validatedData['remarks'];
            $earlyCancelValidatedData['early_cancelled_by'] = getAuthUserCode();

            DB::beginTransaction();
                $storePreOrderEarlyCancel = $this->storePreOrderEarlyCancellationRepository->saveEarlyCancel($earlyCancelValidatedData);
                if($storePreOrderEarlyCancel){
                    $this->storePreOrderRepository->updateStorePreOrderForEarlyCancelled($storePreOrder,$validatedData);
                    $this->storePreOrderStatusLogRepository->saveStatusLog($storePreOrder,$validatedData);
                }
            DB::commit();
            return  $storePreOrderEarlyCancel;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

}
