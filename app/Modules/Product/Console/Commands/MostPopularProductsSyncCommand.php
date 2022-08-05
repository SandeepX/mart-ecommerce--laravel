<?php

namespace App\Modules\Product\Console\Commands;

use App\Modules\Product\Models\MostPopularProduct;
use App\Modules\Product\Models\MostPopularProductSyncLog;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Store\Models\PreOrder\StorePreOrderDetailView;
use App\Modules\Store\Models\StoreOrderDetails;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\DB;

class MostPopularProductsSyncCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sn:mpp-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Its Sync all Most popular products to its respective table.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            $currentTime = Carbon::now();

            $mostPopularProductSyncLog = MostPopularProductSyncLog::create([
                                        'sync_started_at'=>$currentTime
                                    ])->fresh();

            $storeOrderProducts = $this->getStoreOrderProductsQueryWithAmount();
            $storePreOrderProducts = $this->getStorePreOrderProductsQueryWithAmount();

            $products = ProductMaster::select(
                    'products_master.product_code',
                    'warehouses.warehouse_code',
                    DB::raw('SUM(COALESCE(total_preorder_amount,0)+COALESCE(total_preorder_amount,0)) as total_amount')
                )
                ->crossJoin('warehouses')
                ->leftJoinSub($storePreOrderProducts,'store_preorder_products',function ($join){
                    $join->on('products_master.product_code','=','store_preorder_products.preorder_product_code')
                    ->on('warehouses.warehouse_code','=','store_preorder_products.preorder_warehouse_code');
                })
                ->leftJoinSub($storeOrderProducts,'store_order_products',function ($join){
                    $join->on('products_master.product_code','=','store_order_products.order_product_code')
                        ->on('warehouses.warehouse_code','=','store_order_products.order_warehouse_code');
                })
                ->groupBy('warehouses.warehouse_code','products_master.product_code')
                ->having('total_amount','>', 0)
                ->orderBy('total_amount','DESC')
                ->get();

            DB::beginTransaction();

             MostPopularProduct::truncate();

             $productsToInsert = [];
             foreach ($products as $product ){
                 $mostPopularProductData = [];
                 $mostPopularProductData['warehouse_code'] = $product->warehouse_code;
                 $mostPopularProductData['product_code'] = $product->product_code;
                 $mostPopularProductData['total_amount'] = $product->total_amount;
                 $mostPopularProductData['created_at'] = Carbon::now();
                 $mostPopularProductData['updated_at'] = Carbon::now();
                 array_push($productsToInsert,$mostPopularProductData);
             }

             MostPopularProduct::insert($productsToInsert);

            $mostPopularProductSyncLog->update([
                    'sync_status'=>'success',
                    'sync_ended_at'=>Carbon::now(),
                    'sync_remarks' => 'Happy Syncing !'
                ]);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            $mostPopularProductSyncLog->update([
                    'sync_status'=>'failed',
                    'sync_remarks'=>$exception->getMessage()
                ]);
        }
    }

    public function getStoreOrderProductsQueryWithAmount(){
        $storeOrderProducts = StoreOrderDetails::select(
                    'store_order_details.warehouse_code as order_warehouse_code',
                    'store_order_details.product_code as order_product_code',
                    DB::raw('SUM(quantity * unit_rate) as total_order_amount')
                )
                 ->groupBy('warehouse_code','product_code');

        return $storeOrderProducts;
    }

    public function getStorePreOrderProductsQueryWithAmount(){
        $storePreOrderProducts = StorePreOrderDetailView::select(
                'wpl.warehouse_code as preorder_warehouse_code',
                'wpp.product_code as preorder_product_code',
                DB::raw('SUM(quantity * unit_rate) as total_preorder_amount')
            )
            ->join(
                'warehouse_preorder_products as wpp',
                'wpp.warehouse_preorder_product_code',
                '=',
                'store_pre_order_detail_view.warehouse_preorder_product_code'
            )
            ->join('warehouse_preorder_listings as wpl',function ($join){
                $join->on('wpl.warehouse_preorder_listing_code', '=','wpp.warehouse_preorder_listing_code');
            })
            ->groupBy('wpl.warehouse_code','wpp.product_code');

        return $storePreOrderProducts;
    }

}
