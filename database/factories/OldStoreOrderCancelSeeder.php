<?php

use App\Modules\Store\Models\StoreOrderStatusLog;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OldStoreOrderCancelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $storeOrders = \App\Modules\Store\Models\StoreOrder::whereNotIn('delivery_status',[
            'processing',
            'dispatched',
            'cancelled'
        ])->get();
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            foreach ($storeOrders as $storeOrder){
                $storeOrder->update(['delivery_status'=>'cancelled']);

                \App\Modules\Store\Models\StoreOrderDetails::
                where('store_order_code',$storeOrder->store_order_code)
                ->update(['acceptance_status'=>'rejected']) ;

                //Insert Into store_order_status_log table
                StoreOrderStatusLog::updateOrCreate([
                    'store_order_code' => $storeOrder->store_order_code,
                    'status' => 'cancelled'
                ],[
                    'remarks'  => 'Manaul Cancellled',
                    'status_update_date' => Carbon::now(),
                    'updated_by'=>'U00000001'
                ]);

            }
            \Illuminate\Support\Facades\DB::commit();
            return 'DONE !';

        }catch (Exception $exception){
            \Illuminate\Support\Facades\DB::rollBack();
            throw new Exception('Error Occurred !'. $exception->getMessage());
        }
    }
}
