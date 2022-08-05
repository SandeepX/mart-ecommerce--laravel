<?php
namespace  App\Modules\SalesManager\Database\seeds;

use App\Modules\SalesManager\Models\Manager;
use Illuminate\Database\Seeder;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdateStatusRespondedAtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            $managers = Manager::orderBy('id','DESC')->get();
            DB::beginTransaction();
            foreach ($managers as $manager){
                $manager->update(['status_responded_at'=> $manager->updated_at]);
            }
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            echo $exception->getMessage();
        }
    }
}
