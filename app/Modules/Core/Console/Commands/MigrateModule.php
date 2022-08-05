<?php

namespace App\Modules\Core\Console\Commands;

use App\Modules\Core\Classes\CoreHelper;
use Illuminate\Console\Command;


class MigrateModule extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sn:migrate-module {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It migrates all the migration files present in the given module.';

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
        $this->info(" ...... Migrating Module .......");
        $this->info("=============================================");

        $module = $this->argument('module');
        $path = glob(app_path() . '/Modules/'.$module);
        $modules = CoreHelper::moduleDirectories($path);
        unset($modules['Core']);

        $executeMigrations = $this->confirm("Would you like to execute your migration in module : {$module} ? [y|n]", false);
        if ($executeMigrations) {
            foreach ($modules as $key => $module) {
                $this->info("Executing migration for $module");
                $this->call("migrate", ["--path" => "app/Modules/$module/Database/migrations/"]);
            }
            $this->info('Migrations Done Successfully :) ');
        }
    }

}
