<?php

namespace App\Modules\Core\Console\Commands;

use App\Modules\Core\Classes\CoreHelper;
use App\Modules\Core\Console\Commands\Controller\ControllerGenerator;
use App\Modules\Core\Console\Commands\Repository\RepositoryGenerator;
use App\Modules\Core\Console\Commands\Service\ServiceGenerator;
use Illuminate\Console\Command;
use Exception;


class MakeCSR extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sn:make-csr {module} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It makes controller,service and repository class all in once in the given module';


    protected $controllerGenerator;
    protected $serviceGenerator;
    protected $repositoryGenerator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        ControllerGenerator $controllerGenerator,
        ServiceGenerator $serviceGenerator,
        RepositoryGenerator $repositoryGenerator
    )
    {
        parent::__construct();
        $this->controllerGenerator = $controllerGenerator;
        $this->serviceGenerator = $serviceGenerator;
        $this->repositoryGenerator = $repositoryGenerator;
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




//        $make_in = $controllerPath.'/'.$path_option;

//        if (!is_dir($make_in)) {
//            mkdir($make_in, 0777, true);
//        }

        try {
            $this->info("=============================================");
            $this->info(" ...... Creating CSR (Controller + Service + Repository) in {$module} .......");
            $this->info("=============================================");

            $module_path = app_path() . '/Modules/'.$module;

            $controllerPath = $module_path.'/Controllers';
            $servicePath = $module_path.'/Services';
            $repositoryPath = $module_path.'/Repositories';

            if(!(CoreHelper::folder_exist($module_path))){
                $this->error("Module ".'('.$module.')'.' Not Found in App/Modules');
                die;
            }
            if(!(CoreHelper::folder_exist($controllerPath))){
                mkdir($module_path.'/Controllers', 0777, true);
            }

            if(!(CoreHelper::folder_exist($servicePath))){
                mkdir($module_path.'/Services', 0777, true);
            }

            if(!(CoreHelper::folder_exist($repositoryPath))){
                mkdir($module_path.'/Repositories', 0777, true);
            }

            $this->controllerGenerator->create($name, null, $controllerPath, $module);
            $this->serviceGenerator->create($name, null, $servicePath, $module);
            $this->repositoryGenerator->create($name, null, $repositoryPath, $module);

            $this->info("CSR ( Controller + Service + Repository ) Created in {$module} : {$name} ");
        }
        catch(Exception $ex){
            $this->error('Sorry ! CSR could not be created');
        }
    }

}
