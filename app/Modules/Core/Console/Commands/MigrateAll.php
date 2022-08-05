<?php

namespace App\Modules\Core\Console\Commands;



use App\Modules\Core\Classes\CoreHelper;
use Illuminate\Console\Command;

class MigrateAll extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sn:migrate-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It executes all the migration files';



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
        $this->info(" ...... Migrating .......");
        $this->info("=============================================");

        $path = glob(app_path() . '/Modules/*');
        $modules = CoreHelper::moduleDirectories($path);
        unset($modules['Core']);

        $executeMigrations = $this->confirm("Would you like to execute your migrations? [y|n]", false);
        if ($executeMigrations) {
            foreach ($modules as $key => $module) {
                $this->info("Executing migration for $module");
                $this->call("migrate", ["--path" => "app/Modules/$module/Database/migrations/"]);
            }
            $this->info('Migrations Done Successfully :) ');
        }

    }
}
