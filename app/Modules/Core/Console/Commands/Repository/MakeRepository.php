<?php

namespace App\Modules\Core\Console\Commands\Repository;

use App\Modules\Core\Classes\CoreHelper;
use App\Modules\Core\Console\Commands\ModuleCommands\CommandGenerator;
use Illuminate\Console\Command;


class MakeRepository extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sn:make-repository {module} {name} {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It makes a repository class for the specified module';


    protected $repositoryGenerator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(RepositoryGenerator $repositoryGenerator)
    {
        parent::__construct();
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
        $path_option = $this->option('path');

        $this->info("=============================================");
        $this->info(" ...... Creating Repository in {$module} .......");
        $this->info("=============================================");

        $module_path = app_path() . '/Modules/'.$module;
        $controller_path = $module_path.'/Repositories';

        if(!(CoreHelper::folder_exist($module_path))){
            $this->error("Module ".'('.$module.')'.' Not Found in App/Modules');
            die;
        }
        if(!(CoreHelper::folder_exist($controller_path))){
            mkdir($module_path.'/Repositories', 0777, true);
        }

        $make_in = $controller_path.'/'.$path_option;

        if (!is_dir($make_in)) {
            mkdir($make_in, 0777, true);
        }

        $this->repositoryGenerator->create($name,$path_option,$make_in,$module);

        $this->info("Repository Created in {$module} : {$name} ");

    }

}
