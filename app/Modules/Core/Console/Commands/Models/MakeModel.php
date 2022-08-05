<?php

namespace App\Modules\Core\Console\Commands\Models;

use App\Modules\Core\Classes\CoreHelper;
use Illuminate\Console\Command;


/**
 * Class MakeModel
 * @package App\Modules\Core\Console\Commands\Models
 */
class MakeModel extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sn:make-model {module} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It makes a model for the specified module';

    /**
     * @var ModelGenerator
     */
    protected $modelGenerator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ModelGenerator $modelGenerator)
    {
        parent::__construct();
        $this->modelGenerator = $modelGenerator;
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
        $this->info("=============================================");
        $this->info(" ...... Creating Model in {$module} .......");
        $this->info("=============================================");

        $module_path = app_path() . '/Modules/'.$module;
        $model_path = $module_path.'/Models';

        if(!(CoreHelper::folder_exist($module_path))){
            $this->error("Module ".'('.$module.')'.' Not Found in App/Modules');
            die;
        }
        if(!(CoreHelper::folder_exist($model_path))){
            mkdir($module_path.'/Models', 0777, true);
        }
        $this->modelGenerator->create($name,$model_path,$module);

        $this->info("Model Created in {$module} : {$name} ");
    }

}
