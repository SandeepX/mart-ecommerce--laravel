<?php
namespace  App\Modules\SalesManager\Database\seeds;

use App\Modules\Location\Traits\LocationHelper;
use App\Modules\SalesManager\Models\Manager;
use App\Modules\User\Models\User;
use Illuminate\Database\Seeder;
use Exception;
use Illuminate\Support\Facades\DB;

class ManagerDetailSeeder extends Seeder
{
    use LocationHelper;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            DB::beginTransaction();

            $managers = User::with('salesManagerRegistrationStatus')
//                              ->whereHas('userType',function ($query){
//                                $query->where('slug','sales-manager');
//                              })
                              ->where('user_type_code','UT007')
                              ->get();

            foreach ($managers as $manager){

                $managerDetailData = [];
                $managerDetailData['manager_name'] = $manager->name;
                $managerDetailData['manager_email']  = $manager->login_email;
                $managerDetailData['manager_phone_no']  = $manager->login_phone;
                $managerDetailData['manager_photo']  = $manager->avatar;
                $managerDetailData['has_two_wheeler_license']  = $manager->has_two_wheeler_license;
                $managerDetailData['has_four_wheeler_license']  = $manager->has_four_wheeler_license;
                $managerDetailData['is_active']  = $manager->is_active;
                $managerDetailData['temporary_ward_code']  = $manager->temporary_ward;
                $managerDetailData['permanent_ward_code']  = $manager->ward_code;
                $managerDetailData['temporary_full_location']  = $this->getFullLocationPathByLocation($manager->temporaryLocation);
                $managerDetailData['permanent_full_location']  = $this->getFullLocationPathByLocation($manager->permanentLocation);
                $managerDetailData['referral_code']  = $manager->referral_code;
                $managerDetailData['status']  = $manager->salesManagerRegistrationStatus->status;
                $managerDetailData['assigned_area_code']  = $manager->salesManagerRegistrationStatus->assigned_area_code;
                $managerDetailData['remarks']  = $manager->salesManagerRegistrationStatus->remarks;
                $managerDetailData['user_code']  = $manager->user_code;
                $managerDetailData['created_by']  = $manager->created_by;
                $managerDetailData['updated_by']  = $manager->updated_by;
                $managerDetailData['deleted_by'] = $manager->deleted_by;
                $managerDetailData['deleted_at'] = $manager->deleted_at;
                $managerDetailData['created_at'] = $manager->created_at;
                $managerDetailData['updated_at'] = $manager->updated_at;
                $newManager = Manager::create($managerDetailData);
                echo "Seeder Completed for Manager ".$newManager->manager_name." manager code $newManager->manager_code \n";
            }
            DB::commit();
        }catch (Exception $exception){
            DB::rollback();
            echo $exception->getMessage();
        }
    }
}
