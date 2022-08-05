<?php

namespace App\Modules\Core\Console\Commands\Controller;

use App\Modules\Core\Classes\CoreHelper;
use App\Modules\Core\Console\Commands\ModuleCommands\CommandGenerator;
use Illuminate\Console\Command;


class MakeController extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sn:make-controller {module} {name} {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It makes a controller for the specified module';


    protected $controllerGenerator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ControllerGenerator $controllerGenerator)
    {
        parent::__construct();
        $this->controllerGenerator = $controllerGenerator;
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
        $this->info(" ...... Creating Controller in {$module} .......");
        $this->info("=============================================");

        $module_path = app_path() . '/Modules/'.$module;
        $controller_path = $module_path.'/Controllers';

        if(!(CoreHelper::folder_exist($module_path))){
            $this->error("Module ".'('.$module.')'.' Not Found in App/Modules');
            die;
        }
        if(!(CoreHelper::folder_exist($controller_path))){
            mkdir($module_path.'/Controllers', 0777, true);
        }

        $make_in = $controller_path.'/'.$path_option;

        if (!is_dir($make_in)) {
            mkdir($make_in, 0777, true);
        }

        $this->controllerGenerator->create($name,$path_option,$make_in,$module);

        $this->info("Controller Created in {$module} : {$name} ");

    }

}
