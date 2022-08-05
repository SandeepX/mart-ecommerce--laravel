<?php

namespace App\Modules\Core\Console\Commands\Seed;



use App\Modules\Core\Classes\CoreHelper;
use App\Modules\Core\Database\Seeds\SeedMaker;
use Illuminate\Console\Command;

/**
 * Class MakeSeed
 * @package App\Modules\Core\Console\Commands\Seed
 */
class MakeSeed extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sn:make-seed
                          {module : name of the module in Modules Directory}
                           {name : name of the seeder} 
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It makes a seeder file for a module';

    /**
     * @var SeedMaker
     */
    protected $seedMaker;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SeedMaker $seedMaker)
    {
        parent::__construct();
        $this->seedMaker = $seedMaker;

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $module = $this->argument('module');


        $seeder_name = $this->seedMaker->getClassName($module.$name);

        $this->info("=============================================");
        $this->info(" ...... Creating Seeder for {$module} .......");
        $this->info("=============================================");


        $module_path = app_path() . '/Modules/'.$module;
        $database_path = $module_path.'/Database';
        $seed_path = $database_path.'/seeds';

        if(!(CoreHelper::folder_exist($module_path))){
            $this->error("Module ".'('.$module.')'.' Not Found in App/Modules');
            die;
        }
        if(!(CoreHelper::folder_exist($seed_path))){
            mkdir($database_path.'/seeds', 0777, true);
        }


        $this->seedMaker->create($seeder_name,$seed_path);
        $this->info("Seed Created in {$module} : {$seeder_name}");
    }
}
