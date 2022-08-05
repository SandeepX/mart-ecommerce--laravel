<?php

namespace  App\Modules\Types\Database\seeds;

use App\Modules\Types\Models\RejectionParam;
use Illuminate\Database\Seeder;
use App\Modules\User\Models\User;

class RejectionParamSeeder extends Seeder
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
        $rejectionParams = [
            [
                'rejection_code' => 'RP001',
                'rejection_name' => 'Rejection Reason 1',
                'slug' => 'rejection-reason-1',
                'is_active' => 1,
                'created_by' => $superAdminUser->user_code,
                'updated_by' => $superAdminUser->user_code,
            ],
            [
                'rejection_code' => 'RP002',
                'rejection_name' => 'Rejection Reason 2',
                'slug' => 'rejection-reason-2',
                'is_active' => 1,
                'created_by' => $superAdminUser->user_code,
                'updated_by' => $superAdminUser->user_code,
            ]
        ];

        foreach ($rejectionParams as $rejectionParam){
            RejectionParam::updateOrCreate($rejectionParam);
        }

    }
}
