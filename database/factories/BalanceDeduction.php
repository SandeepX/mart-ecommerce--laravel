<?php

use Illuminate\Database\Seeder;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Models\Payments\StoreBalanceMaster;
class BalanceDeduction extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $storeWithDeductedamount =  Store::join ('store_balance_master','store_balance_master.store_code','=','stores_detail.store_code')
            ->where('store_balance_master.transaction_type','refundable')
            ->where('stores_detail.status','approved')
            ->select('stores_detail.store_code')
            ->get()->pluck('store_code')->toArray(); 
           
          


        $totalStores=Store::where('stores_detail.status','approved')->select('store_code')->get()->pluck('store_code')->toArray();

        $remaningStores=array_diff($totalStores,$storeWithDeductedamount);

       
        
      

        foreach($remaningStores as $store){
           
            $balance = StoreBalanceMaster::where('store_code',$store)->latest()->first();
//         dd($balance);
            if($balance){
                $current_balance = $balance['current_balance'];
            }else{
                $current_balance = 0;
            }
            $data = [];
            $data['store_code'] = $store;
            $data['transaction_amount'] = 100000;
            $data['transaction_type'] ='refundable';
            $data['remarks'] = 'mass rerfundable balance deduction 2021-4-16';
            $data['current_balance'] = $current_balance - $data['transaction_amount'];
            $data['created_by'] = "U00000001";
            StoreBalanceMaster::create($data);
            echo 'Store Code: '.$store.' Current Balance Old: '.$current_balance.' Current Balance New: '.$data['current_balance']."\n";
        }
    }


}
