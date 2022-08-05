<?php
namespace  App\Modules\Types\Database\seeds;

use Illuminate\Database\Seeder;
use App\Modules\User\Models\User;
use App\Modules\Types\Models\RegistrationType;

class RegistrationTypeSeeder extends Seeder
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
        $registrationTypes = [
          [
              'registration_type_code' => 'RT001',
              'registration_type_name' => ' Registrar',
              'slug' => 'registrar',
              'is_active' => 1,
              'created_by' => $superAdminUser->user_code,
              'updated_by' => $superAdminUser->user_code,
          ]
        ];


        foreach ($registrationTypes as $registrationType){
            RegistrationType::updateOrCreate($registrationType);
        }


    }
}
