<?php

namespace  App\Modules\Types\Database\seeds;

use App\Modules\Types\Models\CancellationParam;
use Illuminate\Database\Seeder;
use App\Modules\User\Models\User;

class CancellationParamSeeder extends Seeder
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
        $cancellationParams = [
            [
                'cancellation_code' => 'CP001',
                'cancellation_name' => 'Cancel Reason 1',
                'slug' => 'cancel-reason-1',
                'is_active' => 1,
                'created_by' => $superAdminUser->user_code,
                'updated_by' => $superAdminUser->user_code,
            ],
            [
                'cancellation_code' => 'CP002',
                'cancellation_name' => 'Cancel Reason 2',
                'slug' => 'cancel-reason-2',
                'is_active' => 1,
                'created_by' => $superAdminUser->user_code,
                'updated_by' => $superAdminUser->user_code,
            ]
        ];

        foreach ($cancellationParams as $cancellationParam){
            CancellationParam::updateOrCreate($cancellationParam);
        }

    }
}
