<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\PreOrder;


use App\Modules\AlpasalWarehouse\Requests\StorePreOrder\EarlyCancelCreateRequest;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehouseStorePreOrderEarlyCancelService;
use App\Modules\Application\Controllers\BaseController;
use Exception;


class StorePreOrderEarlyCancellationController extends BaseController
{
    public $title = 'Store PreOrder Early Cancellation';
    public $base_route = 'admin.warehouse-pre-orders.store-pre-order.early-cancel';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view = 'warehouse.warehouse-pre-orders.store-pre-orders.early-cancel';

    private $warehouseStorePreOrderEarlyCancelService;

    public function __construct(
        WarehouseStorePreOrderEarlyCancelService $warehouseStorePreOrderEarlyCancelService
    )
    {
        $this->warehouseStorePreOrderEarlyCancelService = $warehouseStorePreOrderEarlyCancelService;
    }

    public function earlyCancelCreate($storePreOrderCode)
    {
        try {
            $storePreOrder = $this->warehouseStorePreOrderEarlyCancelService->createStorePreOrderEarlyCancel($storePreOrderCode);
            return view(Parent::loadViewData($this->module . $this->view . '.create'),
                compact('storePreOrder'));
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function earlyCancelSave(EarlyCancelCreateRequest $request, $storePreOrderCode)
    {
        try {
            $validated = $request->validated();
            $this->warehouseStorePreOrderEarlyCancelService->saveStorePreOrderEarlyCancel($storePreOrderCode,$validated);
            return $request->session()->flash('success', 'Early Cancel success for ' . $storePreOrderCode . '');
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}

