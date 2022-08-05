<?php
/**
 * Created by PhpStorm.
 * User: shramik
 * Date: 3/26/18
 * Time: 12:05 PM
 */

namespace App\Modules\Core\Console\Commands;


use App\Modules\Core\Classes\CoreHelper;
use App\Modules\Core\Database\Migrations\MigrationMaker;
use Illuminate\Console\Command;

class MakeModuleMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sn:make-migration 
                            {module : name of the module in Modules Directory}
                            {file_name : name of the migration file} 
                            {--create=}
                            {--table=}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It creates a new migration file to be included inside Modules';

    /**
     * @var MigrationMaker
     */
    protected $migrationMaker;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MigrationMaker $migrationMaker)
    {
        parent::__construct();
        $this->migrationMaker = $migrationMaker;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("=============================================");
        $this->info(" ...... Creating Migration .......");
        $this->info("=============================================");


        $filename = $this->argument('file_name');
        $module = $this->argument('module');
        $table_to_create = $this->option('create');
        $table_to_migrate = $this->option('table');



        $module_path = app_path() . '/Modules/'.$module;


        if(!(CoreHelper::folder_exist($module_path))){
            $this->error("Module ".'('.$module.')'.' Not Found in App/Modules');
           die;
        }
        $database_path = $module_path.'/Database';
        if(!(CoreHelper::folder_exist($database_path))){
            mkdir($database_path.'/factories', 0777, true);
            mkdir($database_path.'/migrations', 0777, true);
            mkdir($database_path.'/seeds', 0777, true);
        }

        $migration_path = $database_path.'/migrations/';

        if(!is_null($table_to_create))
        {
            $this->migrationMaker->create($filename,$migration_path,$table_to_create,true);
        }

        if(!is_null($table_to_migrate))
        {
            $this->migrationMaker->create($filename,$migration_path,$table_to_migrate,false);
        }

            $this->info('Migration Created : '.$this->migrationMaker->getDatePrefix().'_'.$filename);
        }


}