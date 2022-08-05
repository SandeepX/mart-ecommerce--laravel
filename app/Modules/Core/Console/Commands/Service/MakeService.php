<?php

namespace App\Modules\Core\Console\Commands\Service;

use App\Modules\Core\Classes\CoreHelper;
use App\Modules\Core\Console\Commands\ModuleCommands\CommandGenerator;
use App\Modules\Core\Console\Commands\Service\ServiceGenerator;
use Illuminate\Console\Command;


class MakeService extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sn:make-service {module} {name} {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It makes a service for the specified module';


    protected $serviceGenerator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ServiceGenerator $serviceGenerator)
    {
        parent::__construct();
        $this->serviceGenerator = $serviceGenerator;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $module = $this->argument('module');
        $name = $this->argument('name');
        $path_option = $this->option('path');

        $this->info("=============================================");
        $this->info(" ...... Creating Service in {$module} .......");
        $this->info("=============================================");

        $module_path = app_path() . '/Modules/'.$module;
        $servicePath = $module_path.'/Services';

        if(!(CoreHelper::folder_exist($module_path))){
            $this->error("Module ".'('.$module.')'.' Not Found in App/Modules');
            die;
        }
        if(!(CoreHelper::folder_exist($servicePath))){
            mkdir($module_path.'/Services', 0777, true);
        }

        $make_in = $servicePath.'/'.$path_option;

        if (!is_dir($make_in)) {
            mkdir($make_in, 0777, true);
        }

        $this->serviceGenerator->create($name,$path_option,$make_in,$module);

        $this->info("Service Created in {$module} : {$name} ");

    }

}
