<?php

namespace App\Modules\Core\Console\Commands\Seed;



use App\Modules\Core\Classes\CoreHelper;
use App\Modules\Core\Database\Seeds\SeedRunner;
use Illuminate\Console\Command;

class RunSeed extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sn:run-seed
                            {module}
                            {--class=}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It executes the seed files';

    /**
     * object SeedRunner Class
     */
    protected $seedRunner;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SeedRunner $seedRunner)
    {
        parent::__construct();
        $this->seedRunner =$seedRunner;

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("=============================================");
        $this->info(" ...... Seeding .......");
        $this->info("=============================================");

        $module = $this->argument('module');
        $class = $this->option('class');


        $executeSeeds = $this->confirm("Would you like to execute your seeds? [y|n]", false);
        try{
            if ($executeSeeds) {
                if ($module) {
                    $this->info("Executing for $module");
                    $this->seedRunner->setSeederClass($class);
                    $this->seedRunner->moduleSeed($module);
                }
                $this->info("Seeder Executed Succesfully for Module : {$module}");
            }
        }catch (\Exception $exception){
            $this->warn($exception->getMessage());
        }

    }
}
