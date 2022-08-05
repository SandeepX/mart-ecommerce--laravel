<?php

namespace  App\Modules\Types\Database\seeds;

use Illuminate\Database\Seeder;
use App\Modules\Types\Models\CompanyType;
use App\Modules\User\Models\User;

class CompanyTypeSeeder extends Seeder
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
        $companyTypes = [
            [
                'company_type_code' => 'CT001',
                'company_type_name' => 'National',
                'slug' => 'national',
                'is_active' => 1,
                'created_by' => $superAdminUser->user_code,
                'updated_by' => $superAdminUser->user_code,
            ],
            [
                'company_type_code' => 'CT002',
                'company_type_name' => 'Multi-National',
                'slug' => 'multi-national',
                'is_active' => 1,
                'created_by' => $superAdminUser->user_code,
                'updated_by' => $superAdminUser->user_code,
            ]
        ];

        foreach ($companyTypes as $companyType){
            CompanyType::updateOrCreate($companyType);
        }

    }
}
