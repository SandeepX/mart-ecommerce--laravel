<?php

namespace App\Modules\Core\Console\Commands;



use App\Modules\Core\Classes\CoreHelper;
use Illuminate\Console\Command;

class MigrationRollback extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sn:migrate-rollback
                            {--step=}
                            {--module=}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It rollbacks all the migration files from the modules';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("=============================================");
        $this->info(" ...... Rolling back Migrations  ....... ");
        $this->info("=============================================");

        $step = $this->option('step');
        $module = $this->option('module');

        $module_path = app_path() . '/Modules/'.$module;
        if(!(CoreHelper::folder_exist($module_path))){
            $this->error("Module ".'('.$module.')'.' Not Found in App/Modules');
            die;
        }

        $path = glob(app_path() . '/Modules/*');
        $modules = CoreHelper::moduleDirectories($path);

        unset($modules['Core']);

        $executeMigrations = $this->confirm("Would you like to rollback your migrations? [y|n]", false);
        if ($executeMigrations) {
            if($module){
                $this->info("Executing for $module");
                $this->call("migrate:rollback", ["--path" => "app/Modules/$module/Database/migrations/"]);
            }else{
            foreach ($modules as $key => $module) {
                $this->info("Executing Migrations Rollback for $module");
                $this->call("migrate:rollback", ["--step"=>$step,"--path" => "app/Modules/$module/Database/migrations/"]);
              }
           }
            $this->info('Migrations Done Succesfully :) ');
        }
    }
}
