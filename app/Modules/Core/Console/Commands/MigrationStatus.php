<?php

namespace App\Modules\Core\Console\Commands;



use App\Modules\Core\Classes\CoreHelper;
use Illuminate\Console\Command;

class MigrationStatus extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sn:migrate-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It checks the status of all the migration files in the modules';


    /**
     * @var MigrationMaker
     */
    protected $migrationMaker;

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
        $this->info(" ...... Checking Migration Status .......");
        $this->info("=============================================");

        $path = glob(app_path() . '/Modules/*');
        $modules = CoreHelper::moduleDirectories($path);
        unset($modules['Core']);

            foreach ($modules as $key => $module) {
                $this->info("Executing migration for $module");
                $this->call("migrate:status", ["--path" => "app/Modules/$module/Database/migrations/"]);
            }
    }
}
