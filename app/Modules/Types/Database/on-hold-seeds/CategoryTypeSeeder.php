<?php

namespace  App\Modules\Types\Database\seeds;

use App\Modules\Types\Models\CategoryType;
use Illuminate\Database\Seeder;
use App\Modules\User\Models\User;

class CategoryTypeSeeder extends Seeder
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
        $categoryTypes = [
            [
                'category_type_code' => 'CTC001',
                'category_type_name' => 'Essentials',
                'slug' => 'essentials',
                'is_active' => 1,
                'created_by' => $superAdminUser->user_code,
                'updated_by' => $superAdminUser->user_code,
            ],
            [
                'category_type_code' => 'CTC002',
                'category_type_name' => 'Non-Essentials',
                'slug' => 'non-essentials',
                'is_active' => 1,
                'created_by' => $superAdminUser->user_code,
                'updated_by' => $superAdminUser->user_code,
            ]
        ];

        foreach ($categoryTypes as $categoryType){
            CategoryType::updateOrCreate($categoryType);
        }

    }
}
