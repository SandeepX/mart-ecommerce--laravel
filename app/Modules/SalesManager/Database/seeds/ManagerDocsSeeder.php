<?php
namespace  App\Modules\SalesManager\Database\seeds;

use App\Modules\SalesManager\Models\Manager;
use App\Modules\SalesManager\Models\ManagerDoc;
use App\Modules\User\Models\UserDoc;
use Illuminate\Database\Seeder;
use Exception;
use Illuminate\Support\Facades\DB;

class ManagerDocsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            DB::beginTransaction();

            $userDocs = UserDoc::join('users',function($query){
                                     $query->on('users.user_code','=','user_docs.user_code')->where('users.user_type_code','=','UT007');
                                })
                                ->get();
            $userDocsUsersCode=$userDocs->pluck('user_code')->toArray();
            $managers = Manager::whereIn('user_code',$userDocsUsersCode)->get();

            foreach($userDocs as $userDoc){
                $managerDocData = [];
                $manager = $managers->where('user_code',$userDoc->user_code)->first();
                if(!$manager){
                   continue;
                }
                $managerDocData['manager_code'] = $manager->manager_code;
                $managerDocData['doc_name'] = $userDoc->doc_name;
                $managerDocData['doc'] = $userDoc->doc;
                $managerDocData['is_verified'] = $userDoc->is_verified;
                $managerDocData['verified_by'] = $userDoc->verified_by;
                $managerDocData['doc_number'] = $userDoc->doc_number;
                $managerDocData['doc_issued_district'] = $userDoc->doc_issued_district;
                $managerDoc =  ManagerDoc::create($managerDocData);

                echo "Manager docs Transfer to Manager docs table ".$managerDoc->manager_doc_code." \n";
            }

            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            echo $exception->getMessage();
        }
    }
}
