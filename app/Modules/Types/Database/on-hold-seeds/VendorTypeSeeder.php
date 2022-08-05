<?php

namespace  App\Modules\Types\Database\seeds;

use Illuminate\Database\Seeder;
use App\Modules\Types\Models\VendorType;

use App\Modules\User\Models\User;

class VendorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminUser = User::whereHas('userType',function($query){
            $query->where('slug','super-admin');
        })->first();
        $vendorTypes = [
            [
                'vendor_type_code' => 'VT001',
                'vendor_type_name' => 'Manufacturer',
                'slug' => 'manufacturer',
                'is_active' => 1,
                'created_by' => $superAdminUser->user_code,
                'updated_by' => $superAdminUser->user_code,
            ],
            [
                'vendor_type_code' => 'VT002',
                'vendor_type_name' => 'Supplier',
                'slug' => 'supplier',
                'is_active' => 1,
                'created_by' => $superAdminUser->user_code,
                'updated_by' => $superAdminUser->user_code,
            ]
        ];

        foreach ($vendorTypes as $vendorType){
            VendorType::updateOrCreate($vendorType);
        }
    }
}
