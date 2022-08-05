<?php


namespace  App\Modules\Location\Database\seeds;

use Illuminate\Database\Seeder;
use DB;
use File;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::statement('SET FOREIGN_KEY_CHECKS=0');
        // DB::statement('TRUNCATE TABLE location_hierarchy');
        // DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $sqlFolder = dirname(__DIR__,1).'/Sql/';
        
    
        DB::unprepared(File::get($sqlFolder.'location.sql'));  
    }
}
