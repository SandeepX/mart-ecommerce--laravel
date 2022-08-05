<?php


namespace App\Modules\AlpasalWarehouse\Repositories\StockTransfer;


use App\Modules\AlpasalWarehouse\Helpers\StockTransfer\StockTransferHelper;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductPriceHelper;
use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseReceivedStockTransferDetail;
use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseStockTransfer;
use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseStockTransferDetail;
use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseStockTransferMasterMeta;
use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseTransferLoss;
use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseTransferStock;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductPriceMaster;
use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductStock;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Models\ProductImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WarehouseStockTransferRepository
{
    use ImageService;
    protected $model;
    protected $warehouseProductMaster;
    public function __construct(
        WarehouseStockTransfer $warehouseStockTransfer,
        WarehouseProductMaster $warehouseProductMaster
    )
    {
        $this->model = $warehouseStockTransfer;
        $this->warehouseProductMaster = $warehouseProductMaster;
    }

    public function getAllWarehouseStockTransfer($filterParameters, $paginateBy = null)
    {
        $stockTransferDetails = WarehouseStockTransferDetail::select(
            'stock_transfer_details_code',
            'stock_transfer_master_code',
            DB::raw('count(stock_transfer_master_code) as total_products')
            )
            ->groupBy('stock_transfer_master_code');

        $paginatedBy = isset($paginateBy) ? $paginateBy : 10;

        $stockTransfers = DB::table('warehouse_stock_transfer_master')->where('source_warehouse_code', getAuthWarehouseCode())
            ->select(
                'warehouse_stock_transfer_master.stock_transfer_master_code',
                'warehouse_stock_transfer_master.remarks',
                'warehouse_stock_transfer_master.destination_warehouse_code',
                'warehouse_stock_transfer_master.status',
                'warehouse_stock_transfer_master.created_at',
                'st_details.total_products',
                'warehouses.warehouse_name'
            )->leftJoinSub($stockTransferDetails, 'st_details', function ($join) {
                $join->on('st_details.stock_transfer_master_code', '=', 'warehouse_stock_transfer_master.stock_transfer_master_code');
            })
            ->join('warehouses', 'warehouse_stock_transfer_master.destination_warehouse_code', '=', 'warehouses.warehouse_code')
            ->when(isset($filterParameters['destination_warehouse_name']), function ($query) use ($filterParameters) {
                $query->where('warehouse_stock_transfer_master.destination_warehouse_code', 'like', '%'.$filterParameters['destination_warehouse_name'].'%')
                    ->orWhere('warehouses.warehouse_name', 'like', '%'.$filterParameters['destination_warehouse_name'].'%');
            })
            ->when(isset($filterParameters['delivery_status']), function ($query) use ($filterParameters) {
                $query->where('warehouse_stock_transfer_master.status', 'like', '%'.$filterParameters['delivery_status'].'%');
            })
            ->when(isset($filterParameters['transaction_date_from']), function ($query) use ($filterParameters) {
                $query->whereDate('warehouse_stock_transfer_master.created_at', '>=', date('y-m-d', strtotime($filterParameters['transaction_date_from'])));
            })
            ->when(isset($filterParameters['transaction_date_to']), function ($query) use ($filterParameters) {
                $query->whereDate('warehouse_stock_transfer_master.created_at', '<=', date('y-m-d', strtotime($filterParameters['transaction_date_to'])));
            })
            ->orderBy('warehouse_stock_transfer_master.created_at', 'DESC')
            ->paginate($paginatedBy);
        return $stockTransfers;
    }

    public function addWarehouseStockTransfer($validatedStockTransferWarehouse)
    {
        $stockTransferWarehouse = $this->model->create([
            'status' => 'draft',
            'remarks' => $validatedStockTransferWarehouse['remarks'],
            'created_by' => getAuthUserCode(),
            'source_warehouse_code' => getAuthWarehouseCode(),
            'destination_warehouse_code' => $validatedStockTransferWarehouse['warehouse_name'],
        ]);
        return $stockTransferWarehouse->fresh();
    }

    public function create($validatedData){
        $stockTransferWarehouse = $this->model->create($validatedData);
        return $stockTransferWarehouse->fresh();
    }

    public function getWarehouseStockTransferByCode($stockTransferMasterCode,$with=[],$select = "*")
    {
        return $this->model->with($with)->select($select)->where('stock_transfer_master_code', $stockTransferMasterCode)->firstOrFail();
    }

    public function getWarehouseProducts($filterParameters,  $paginateBy)
    {

        $productImages = ProductImage::select(
            DB::raw('min(product_images.id) as product_image_id'),
            'product_images.product_code',
            'product_images.image'
        )->groupBy('product_images.product_code');
        $warehouseProducts = WarehouseProductMaster::select(
                'warehouse_product_master.warehouse_product_master_code',
                'wh_product_category_filter_view.warehouse_code',
                'warehouse_product_master.product_code',
                'warehouse_product_master.product_variant_code',
                'products_master.product_name',
                'product_variants.product_variant_name',
                'product_image.image',
                'products_master.brand_code',
                'wh_product_category_filter_view.category_code',
                'warehouse_product_master.current_stock',
                'product_packaging_details.product_packaging_detail_code'
            )
            ->join('vendors_detail', 'warehouse_product_master.vendor_code', '=', 'vendors_detail.vendor_code')
            ->join('products_master', 'warehouse_product_master.product_code', '=', 'products_master.product_code')
            ->join('brands', 'products_master.brand_code', '=', 'brands.brand_code')
            ->join('wh_product_category_filter_view', function ($join){
                $join->on(function($join) {
                    $join->on('warehouse_product_master.warehouse_code', '=', 'wh_product_category_filter_view.warehouse_code');
                    $join->on('warehouse_product_master.product_code', '=', 'wh_product_category_filter_view.product_code');
                    $join->on(function ($q) {
                        $q->on('warehouse_product_master.product_variant_code', 'wh_product_category_filter_view.product_variant_code')
                            ->orWhere(function ($q) {
                                $q->where('warehouse_product_master.product_variant_code', null)->where('wh_product_category_filter_view.product_variant_code', null);
                            });
                    });
                });
            })
            ->join('product_packaging_details',function($join){
                $join->on('product_packaging_details.product_code','warehouse_product_master.product_code');
                    $join->on(function ($q) {
                        $q->on('product_packaging_details.product_variant_code','=','warehouse_product_master.product_variant_code')
                            ->orWhere(function ($q) {
                                $q->where('product_packaging_details.product_variant_code', null)->where('warehouse_product_master.product_variant_code', null);
                            });
                    });
            })
            ->leftJoin('product_variants', function ($join) {
                $join->on(function($join) {
                    $join->on('warehouse_product_master.product_variant_code', '=', 'product_variants.product_variant_code');
                    $join->on(function ($q) {
                        $q->on('warehouse_product_master.product_variant_code', 'product_variants.product_variant_code')
                            ->orWhere(function ($q) {
                                $q->where('warehouse_product_master.product_variant_code', null)->where('product_variants.product_variant_code', null);
                            });
                    });
                });
            })
            ->when(isset($filterParameters['vendor_name']), function ($query) use ($filterParameters) {
                $query->where('vendors_detail.vendor_name', 'like', '%'.$filterParameters['vendor_name'].'%')
                    ->orWhere('vendors_detail.vendor_code', 'like', '%'.$filterParameters['vendor_name'].'%');
            })
            ->when(isset($filterParameters['product_name']), function ($query) use ($filterParameters) {
                $query->where('products_master.product_name', 'like', '%'.$filterParameters['product_name'].'%');
                    //->orWhere('warehouse_product_master.product_code', 'like', '%'.$filterParameters['product_name'].'%');
            })
            ->when(isset($filterParameters['brand_name']), function ($query) use ($filterParameters) {
                $query->where('brands.brand_code', 'like', '%'.$filterParameters['brand_name'].'%');
            })
            ->when(isset($filterParameters['category_names']), function ($query) use ($filterParameters) {
                $query->whereIn('wh_product_category_filter_view.category_code',  $filterParameters['category_names']);
            })
            ->joinSub($productImages, 'product_image', function ($join) {
                $join->on('warehouse_product_master.product_code', '=', 'product_image.product_code');
            })
          //  ->join('warehouse_product_stock_view', 'warehouse_product_master.warehouse_product_master_code', '=', 'warehouse_product_stock_view.code')
            ->where('warehouse_product_master.current_stock', '>' , 0)
            ->groupBy('warehouse_product_master.product_code', 'warehouse_product_master.product_variant_code')
            ->orderBy('products_master.product_name')
            ->orderBy('warehouse_product_master.created_by', 'desc')
            ->where('warehouse_product_master.is_active', 1)
            ->where('warehouse_product_master.warehouse_code', getAuthWarehouseCode());

        $warehouseProducts= $warehouseProducts->paginate($paginateBy);
        return $warehouseProducts;
    }

    public function getProductByWarehouseProductMasterCode($warehouse_product_code, $stockTransferCode)
    {
        $warehouseStockTransferDetails = WarehouseStockTransferDetail::select(
            'warehouse_stock_transfer_details.*',
            'warehouse_product_master.product_code',
            'warehouse_product_master.product_variant_code',
            'products_master.product_name',
            'product_variants.product_variant_name',
            'vendor_product_price_view.vendor_price'
        )
            ->join('warehouse_product_master',function($join){
                $join->on('warehouse_product_master.warehouse_product_master_code','warehouse_stock_transfer_details.warehouse_product_master_code');
            })
            ->join('products_master', 'warehouse_product_master.product_code', '=', 'products_master.product_code')
            ->leftJoin('product_variants', 'warehouse_product_master.product_variant_code', '=', 'product_variants.product_variant_code')
            ->join('vendor_product_price_view', function ($join) {
                $join->on(function($join) {
                    $join->on('warehouse_product_master.product_code', '=', 'vendor_product_price_view.product_code');
                    $join->on(function ($q) {
                        $q->on('warehouse_product_master.product_variant_code', 'vendor_product_price_view.product_variant_code')
                            ->orWhere(function ($q) {
                                $q->where('warehouse_product_master.product_variant_code', null)->where('vendor_product_price_view.product_variant_code', null);
                            });
                    });
                });
            })
            ->where('warehouse_stock_transfer_details.warehouse_product_master_code', $warehouse_product_code)
            ->where('warehouse_stock_transfer_details.stock_transfer_master_code', $stockTransferCode)->first();

        if(empty($warehouseStockTransferDetails)) {
            $product = WarehouseProductMaster::select(
                'warehouse_product_master.warehouse_product_master_code',
                'warehouse_product_master.product_code',
                'warehouse_product_master.product_variant_code',
                'products_master.product_name',
                'product_variants.product_variant_name',
                'vendor_product_price_view.vendor_price'
            )
                ->where('warehouse_product_master.warehouse_product_master_code', $warehouse_product_code)
                ->join('products_master', 'warehouse_product_master.product_code', '=', 'products_master.product_code')
                ->leftJoin('product_variants', 'warehouse_product_master.product_variant_code', '=', 'product_variants.product_variant_code')
                ->join('vendor_product_price_view', function ($join) {
                    $join->on(function($join) {
                        $join->on('warehouse_product_master.product_code', '=', 'vendor_product_price_view.product_code');
                        $join->on(function ($q) {
                            $q->on('warehouse_product_master.product_variant_code', 'vendor_product_price_view.product_variant_code')
                                ->orWhere(function ($q) {
                                    $q->where('warehouse_product_master.product_variant_code', null)->where('vendor_product_price_view.product_variant_code', null);
                                });
                        });
                    });
                })
                ->first();
            return $product;
        }
        return false;
    }

    public function storeStockTransferProductsDetails($products, $stockTransferCode, $status = null)
    {
        $stockTransferWarehouse = $this->model->where('stock_transfer_master_code', $stockTransferCode)->firstOrFail();
        if ($stockTransferWarehouse->status == 'draft') {
            $stockTransferWarehouse->update([
                'status' => $status ? $status : 'draft',
            ]);
        }

        $data = [];
        foreach ($products as $key => $product) {

            if (empty($product['stock_transfer_details_code'])) {

                $data[] = WarehouseStockTransferDetail::create([
                    'stock_transfer_master_code' => $stockTransferCode,
                    'warehouse_product_master_code' => $product['warehouse_product_master_code'],
                    'sending_quantity' => $product['product_quantity'],
                    'created_by' => getAuthUserCode(),
                ]);

            } else {
                $warehouseStockTransferDetail = WarehouseStockTransferDetail::where('stock_transfer_details_code', $product['stock_transfer_details_code'])->firstOrFail();
                $data[] = $warehouseStockTransferDetail->update([
                    'sending_quantity' => $product['product_quantity'],
                    'created_by' => getAuthUserCode(),
                ]);
            }

            if ($status == 'sent') {
                $productStock = WarehouseProductStock::create([
                    'warehouse_product_master_code' => $product['warehouse_product_master_code'],
                    'quantity' => $product['product_quantity'],
                    'action' => 'stock-transfer',
                ]);
                WarehouseTransferStock::create([
                    'warehouse_product_stock_code' => $productStock->warehouse_product_stock_code,
                    'stock_transfer_master_code' => $stockTransferCode
                ]);
            }
        }

        return $data;
    }

    public function getWarehouseStockTransferProductsDetailsByCode( $stockTransferCode, $filterParameters = [], $paginateBy = null)
    {
        $paginatedBy = isset($paginateBy) ? $paginateBy : 10;
        $priceCondition = isset($filterParameters['price_condition']) && in_array($filterParameters['price_condition'], ['>','<', '>=','<=','=']) ? true : false;

        $warehouseReceiveStockProducts = WarehouseReceivedStockTransferDetail::where('warehouse_received_stock_transfer_details.stock_transfer_master_code', $stockTransferCode)
                                                ->select(
                                                    'warehouse_received_stock_transfer_details.received_stock_transfer_details_code',
                                                    'warehouse_received_stock_transfer_details.stock_transfer_master_code',
                                                    'warehouse_received_stock_transfer_details.warehouse_product_master_code',
                                                    'warehouse_received_stock_transfer_details.package_code',
                                                    'warehouse_received_stock_transfer_details.received_quantity',
                                                    'warehouse_product_master.product_code',
                                                    'warehouse_product_master.product_variant_code',
                                                    DB::raw('GROUP_CONCAT(CONCAT(warehouse_received_stock_transfer_details.package_quantity," ",package_types.package_name),"") as received_package')
                                                )
                                                ->join( 'warehouse_product_master',  'warehouse_received_stock_transfer_details.warehouse_product_master_code', '=','warehouse_product_master.warehouse_product_master_code')
                                                ->join('products_master', 'warehouse_product_master.product_code', '=', 'products_master.product_code')
                                                ->leftJoin('product_variants', function ($join){
                                                     $join->on('warehouse_product_master.product_variant_code', '=', 'product_variants.product_variant_code');
                                                })
                                                ->leftJoin('package_types','package_types.package_code','=','warehouse_received_stock_transfer_details.package_code')
                                                ->groupBy(
                                                    'warehouse_received_stock_transfer_details.stock_transfer_master_code',
                                                    'warehouse_received_stock_transfer_details.warehouse_product_master_code'
                                                )
                                                ->orderBy('warehouse_received_stock_transfer_details.created_at','DESC');


        $warehouseStockTransferProducts = WarehouseStockTransferDetail::select(
                'warehouse_stock_transfer_details.stock_transfer_details_code',
                'warehouse_stock_transfer_details.stock_transfer_master_code',
                'warehouse_stock_transfer_details.warehouse_product_master_code',
                'warehouse_stock_transfer_details.package_code',

                'products_master.product_name',
                'warehouse_product_master.product_code',
                'product_variants.product_variant_name',
                'warehouse_product_master.product_variant_code',
                'warehouse_product_master.vendor_code',
                'vendor_product_price_view.vendor_price',
                'warehouse_stock_transfer_details.sending_quantity',
                 DB::raw('GROUP_CONCAT(CONCAT(warehouse_stock_transfer_details.package_quantity," ",package_types.package_name),"") as sending_package'),
                'received_stocks.received_quantity',
                'received_stocks.received_package',
                'warehouse_transfer_loss_master.quantity as loss_quantity',
                 DB::raw('GROUP_CONCAT(CONCAT(warehouse_transfer_loss_master.package_quantity," ",loss_package_types.package_name),"") as loss_package'),

                'micro_unit_package_name.package_name as micro_unit_name',
                'unit_package_name.package_name as unit_name',
                'macro_unit_package_name.package_name as macro_unit_name',
                'super_unit_package_name.package_name as super_unit_name'
            )
            ->join( 'warehouse_product_master',  'warehouse_stock_transfer_details.warehouse_product_master_code', '=','warehouse_product_master.warehouse_product_master_code')
            ->join('products_master', 'warehouse_product_master.product_code', '=', 'products_master.product_code')
            ->leftJoin('product_variants', function ($join){
                $join->on('warehouse_product_master.product_variant_code', '=', 'product_variants.product_variant_code');
            })
            ->leftJoin('package_types','package_types.package_code','=','warehouse_stock_transfer_details.package_code')
            ->join('vendor_product_price_view', function ($join) {
                $join->on(function($join) {
                    $join->on('warehouse_product_master.product_code', '=', 'vendor_product_price_view.product_code');
                    $join->on(function ($q) {
                        $q->on('warehouse_product_master.product_variant_code', '=', 'vendor_product_price_view.product_variant_code')
                            ->orWhere(function ($q) {
                                $q->where('warehouse_product_master.product_variant_code', null)->where('vendor_product_price_view.product_variant_code', null);
                            });
                    });
                });
            })
            ->leftJoin('warehouse_transfer_loss_master', function ($join) {
                $join->on('warehouse_stock_transfer_details.stock_transfer_master_code','=','warehouse_transfer_loss_master.stock_transfer_master_code');
                $join->on('warehouse_stock_transfer_details.warehouse_product_master_code','=', 'warehouse_transfer_loss_master.warehouse_product_master_code');
            })
            ->leftJoin('package_types as loss_package_types',function ($join){
                $join->on('warehouse_transfer_loss_master.package_code','=','loss_package_types.package_code');
            })

            ->leftJoin('product_packaging_history', function ($join) {
                $join->on('product_packaging_history.product_packaging_history_code',
                    '=', 'warehouse_stock_transfer_details.product_packaging_history_code');
            })->leftJoin('package_types as unit_package_name', function ($join) {
                $join->on('unit_package_name.package_code',
                    '=',
                    'product_packaging_history.unit_code');
            })->leftJoin('package_types as micro_unit_package_name', function ($join) {
                $join->on('micro_unit_package_name.package_code',
                    '=',
                    'product_packaging_history.micro_unit_code');
            })->leftJoin('package_types as macro_unit_package_name', function ($join) {
                $join->on('macro_unit_package_name.package_code',
                    '=',
                    'product_packaging_history.macro_unit_code');
            })->leftJoin('package_types as super_unit_package_name', function ($join) {
                $join->on('super_unit_package_name.package_code',
                    '=',
                    'product_packaging_history.super_unit_code');
            })
            ->where('warehouse_stock_transfer_details.stock_transfer_master_code', $stockTransferCode)
            ->leftJoinSub($warehouseReceiveStockProducts,'received_stocks',function ($join) {
                $join->on('warehouse_product_master.product_code','=','received_stocks.product_code');
                $join->on(function ($q) {
                    $q->on('warehouse_product_master.product_variant_code', '=', 'received_stocks.product_variant_code')
                        ->orWhere(function ($q) {
                            $q->where('warehouse_product_master.product_variant_code', null)->where('received_stocks.product_variant_code', null);
                        });
                });
            })
            ->when(isset($filterParameters['product_name']), function ($query) use ($filterParameters) {
                $query->where(function ($query) use ($filterParameters){
                    $query->where('products_master.product_name', 'like', '%'.$filterParameters['product_name'].'%')
                        ->orWhere('warehouse_product_master.product_code', 'like', '%'.$filterParameters['product_name'].'%');
                });

            })
            ->when(isset($filterParameters['variant_name']), function ($query) use ($filterParameters) {
                $query->where(function ($query) use ($filterParameters){
                    $query->where('product_variants.product_variant_name', 'like', '%'.$filterParameters['variant_name'].'%')
                        ->orWhere('warehouse_product_master.product_variant_code', 'like', '%'.$filterParameters['variant_name'].'%');
                });
            })
            ->when($priceCondition && isset($filterParameters['total_price']),function ($query) use($filterParameters) {
                $query->where('total_price', $filterParameters['price_condition'], $filterParameters['total_price']);
            })

            ->groupBy(
                'warehouse_stock_transfer_details.stock_transfer_master_code',
                'warehouse_stock_transfer_details.warehouse_product_master_code'
            )
            ->orderBy('warehouse_stock_transfer_details.created_at','DESC')
            ->paginate($paginatedBy);


        return $warehouseStockTransferProducts;
    }

    public function getAllReceivedWarehouseStockTransfers($filterParameters, $paginateBy = null)
    {
         $stockTransferDetails = WarehouseStockTransferDetail::select(
                'stock_transfer_details_code',
                'stock_transfer_master_code',
                DB::raw('count(stock_transfer_master_code) as total_products')
            )
            ->groupBy('stock_transfer_master_code');

        $paginatedBy = isset($paginateBy) ? $paginateBy : 10;
        $receivedWarehouseStockTransfers = DB::table('warehouse_stock_transfer_master')
            ->where('destination_warehouse_code', getAuthWarehouseCode())
            ->whereIn('warehouse_stock_transfer_master.status', ['sent', 'received'])
            ->select(
                'warehouse_stock_transfer_master.remarks',
                'warehouse_stock_transfer_master.source_warehouse_code',
                'warehouse_stock_transfer_master.status',
                'warehouse_stock_transfer_master.created_at',
                'st_details.total_products',
                'st_details.stock_transfer_master_code',
                'warehouses.warehouse_name'
            )->joinSub($stockTransferDetails, 'st_details', function ($join) {
                $join->on('st_details.stock_transfer_master_code', '=', 'warehouse_stock_transfer_master.stock_transfer_master_code');
            })
            ->join('warehouses', 'warehouse_stock_transfer_master.source_warehouse_code', '=', 'warehouses.warehouse_code')
            ->when(isset($filterParameters['source_warehouse_name']), function ($query) use ($filterParameters) {
                $query->where('warehouse_stock_transfer_master.source_warehouse_code', 'like', '%'.$filterParameters['source_warehouse_name'].'%')
                    ->orWhere('warehouses.warehouse_name', 'like', '%'.$filterParameters['source_warehouse_name'].'%');
            })
            ->when(isset($filterParameters['delivery_status']), function ($query) use ($filterParameters) {
                $query->where('warehouse_stock_transfer_master.status', 'like', '%'.$filterParameters['delivery_status'].'%');
            })
            ->when(isset($filterParameters['transaction_date_from']), function ($query) use ($filterParameters) {
                $query->whereDate('warehouse_stock_transfer_master.created_at', '>=', date('y-m-d', strtotime($filterParameters['transaction_date_from'])));
            })
            ->when(isset($filterParameters['transaction_date_to']), function ($query) use ($filterParameters) {
                $query->whereDate('warehouse_stock_transfer_master.created_at', '<=', date('y-m-d', strtotime($filterParameters['transaction_date_to'])));
            })
            ->orderBy('warehouse_stock_transfer_master.created_at', 'DESC')
            ->paginate($paginatedBy);
        return $receivedWarehouseStockTransfers;
    }

    public function updateWarehouseReceivedProductsQuantity($products, $stockTransferCode)
    {
        $warehouseStockTransfer = $this->model->where('stock_transfer_master_code', $stockTransferCode)->firstOrFail();
        if ($warehouseStockTransfer->status == 'sent')  {
            $warehouseStockTransfer->update([
                'status' => 'received',
            ]);
        }

        $receivedProducts = [];
        foreach ($products as $product) {
            $warehouseStockTransferDetails = WarehouseStockTransferDetail::where('stock_transfer_details_code', $product['stock_transfer_details_code'])->firstOrFail();
            $productPackagingDetail = StockTransferHelper::
            getOrderedProductPackagingDetailForStockTransfer($product['product_code'],$product['product_variant_code'],$product['package_code']);

            $convertedOrderedMicroQuantity = ProductUnitPackagingHelper::convertToMicroPackagingUnitQuantity(
                $productPackagingDetail, $product['product_quantity'],$productPackagingDetail->ordered_package_type);

          //  $currentStock = $this->warehouseProductStockRepository->findCurrentProductStockInWarehouse($wpmCode);
            $receivedProducts[] = $warehouseStockTransferDetails->update([
                'received_quantity' => $product['product_quantity'],
            ]);

            if($warehouseStockTransferDetails->sending_quantity > $product['product_quantity']) {
                WarehouseTransferLoss::create([
                    'stock_transfer_master_code' => $stockTransferCode,
                    'stock_transfer_details_code' => $product['stock_transfer_details_code'],
                    'quantity' => $warehouseStockTransferDetails->sending_quantity - $product['product_quantity'],
                    'reason' => 'transfer-loss'
                ]);
            }

            $warehouseProductCode = WarehouseProductMaster::select('warehouse_product_master_code')
                ->where('warehouse_code', getAuthWarehouseCode())
                ->where('product_code', $product['product_code'])
                ->where('product_variant_code', $product['product_variant_code'])->first();

            if(isset($warehouseProductCode)) {
                $productStock = WarehouseProductStock::create([
                    'warehouse_product_master_code' => $warehouseProductCode->warehouse_product_master_code,
                    'quantity' => $convertedOrderedMicroQuantity,
                    'action' => 'received-stock-transfer',
                ]);
            } else {
                $warehouseProductMasterCode = WarehouseProductMaster::create([
                    'warehouse_code' => getAuthWarehouseCode(),
                    'product_code' => $product['product_code'],
                    'product_variant_code' => $product['product_variant_code'],
                    'vendor_code' => $product['vendor_code'],
                    'is_active' => 1,
                ]);
                $productStock = WarehouseProductStock::create([
                    'warehouse_product_master_code' => $warehouseProductMasterCode->warehouse_product_master_code,
                    'quantity' => $convertedOrderedMicroQuantity,
                    'action' => 'received-stock-transfer',
                ]);

                $warehouseProductPrice = WarehouseProductPriceHelper::findWarehouseProductPriceByWarehouseProductCode($product['warehouse_product_master_code']);

                if($warehouseProductPrice) {
                    WarehouseProductPriceMaster::create([
                        'warehouse_product_master_code' => $warehouseProductMasterCode->warehouse_product_master_code,
                        'mrp' => $warehouseProductPrice->mrp,
                        'admin_margin_type' => $warehouseProductPrice->admin_margin_type,
                        'admin_margin_value' => $warehouseProductPrice->admin_margin_value,
                        'wholesale_margin_type' => $warehouseProductPrice->wholesale_margin_type,
                        'wholesale_margin_value' => $warehouseProductPrice->wholesale_margin_value,
                        'retail_margin_type' => $warehouseProductPrice->retail_margin_type,
                        'retail_margin_value' => $warehouseProductPrice->retail_margin_value,
                    ]);
                }
            }

            WarehouseTransferStock::create([
                'warehouse_product_stock_code' => $productStock->warehouse_product_stock_code,
                'stock_transfer_master_code' => $stockTransferCode
            ]);
        }

        return $receivedProducts;
    }

    public function getProductPackageHistoryByTransferAndWarehouseProductCode($stockTransferMasterCode,$warehouseProductMasterCode,$with=['productPackagingHistory']){
        $warehouseStockTransferDetails =   WarehouseStockTransferDetail::with($with)
                                            ->where('stock_transfer_master_code',$stockTransferMasterCode)
                                            ->where('warehouse_product_master_code',$warehouseProductMasterCode)
                                            ->first();
        if($warehouseStockTransferDetails){
           return $warehouseStockTransferDetails->productPackagingHistory;
        }
        return false;
    }

    public function getTotalSendingMicroOrderedQtyByTransferAndWarehouseProductCode($stockTransferMasterCode,$warehouseProductMasterCode){
        $totalMicroOrderedQty =   WarehouseStockTransferDetail::where('stock_transfer_master_code',$stockTransferMasterCode)
                                                                 ->where('warehouse_product_master_code',$warehouseProductMasterCode)
                                                                 ->sum('sending_quantity');
        return (int) $totalMicroOrderedQty;
    }

    public function deleteWarehouseStockTransferDetailsByCode($stockTransferDetailsCode, $stockTransferCode)
    {
        $stockTransferDetail = WarehouseStockTransferDetail::where('stock_transfer_details_code', $stockTransferDetailsCode)
            ->where('stock_transfer_master_code', $stockTransferCode)
            ->firstOrFail();
        return $stockTransferDetail->delete();
    }

    public function addStockTransferMasterMeta($request, $stockTransferCode)
    {
        try {
            if ($request->hasFile('file') && $request->file('file')) {
                $filename = $this->storeImageInServer($request->file('file'), WarehouseStockTransferMasterMeta::IMAGE_PATH);
            }
            $stockTransferMasterMeta = WarehouseStockTransferMasterMeta::create([
                'stock_transfer_master_code' => $stockTransferCode,
                'key' => Str::snake($request->get('key')),
                'value' => isset($filename) ? $filename : $request->get('value'),
                'is_active' => 1
            ]);
            return $stockTransferMasterMeta->fresh();
        }catch (\Exception $e) {
            throw $e;
        }
    }

    public function getStockTransferMetaByStockTransferCode($stockTransferCode)
    {
        $stockTransferMasterMeta = WarehouseStockTransferMasterMeta::where('stock_transfer_master_code', $stockTransferCode)->get();
        return $stockTransferMasterMeta;
    }


    public function addProductToStockTransfer($product, $stockTransferCode,$convertedOrderedMicroQuantity,$warehouseProductMaster, $status = null)
    {

        $stockTransferWarehouse = $this->model->where('stock_transfer_master_code', $stockTransferCode)->firstOrFail();
        if ($stockTransferWarehouse->status == 'draft') {
            $stockTransferWarehouse->update([
                'status' => $status ? $status : 'draft',
            ]);
        }
        //dd($stockTransferWarehouse);
        $data = [];

            if (empty($product['stock_transfer_details_code'])) {
                $data[] = WarehouseStockTransferDetail::create([
                    'stock_transfer_master_code' => $stockTransferCode,
                    'warehouse_product_master_code' => $product['warehouse_product_master_code'],
                    'sending_quantity' => $product['product_quantity'],
                    'package_quantity' => (int) $product['package_quantity'],
                    'package_code' => $product['package_code'],
                    'product_packaging_history_code' => $product['product_packaging_history_code'],
                    'created_by' => getAuthUserCode(),
                ]);
            } else {
                $warehouseStockTransferDetail = WarehouseStockTransferDetail::where('stock_transfer_details_code', $product['stock_transfer_details_code'])
                    ->firstOrFail();
                $data[] = $warehouseStockTransferDetail->update([
                    'sending_quantity' => $product['product_quantity'],
                    'package_quantity' => (int) $product['package_quantity'],
                    'package_code' => $product['package_code'],
                    'product_packaging_history_code' => $product['product_packaging_history_code'],
                    'created_by' => getAuthUserCode(),
                ]);
            }

            if ($status == 'sent') {
                $productStock = WarehouseProductStock::create([
                    'warehouse_product_master_code' => $product['warehouse_product_master_code'],
                    'quantity' => $convertedOrderedMicroQuantity,
                    'package_qty' => (int) $product['package_quantity'],
                    'package_code' => $product['package_code'],
                    'product_packaging_history_code' => $product['product_packaging_history_code'],
                    'reference_code' => $stockTransferCode,
                    'action' => 'stock-transfer',
                ]);

                $currentStock = $warehouseProductMaster->current_stock - (int) $convertedOrderedMicroQuantity;
                $warehouseProductMaster->update(['current_stock' => $currentStock]);
            }

        return $data;
    }

    public function updateStatusOfStockTransferMaster(WarehouseStockTransfer $stockTransferMaster,$validatedData){
         $stockTransferMaster->update($validatedData);
         return $stockTransferMaster->fresh();
    }
}
