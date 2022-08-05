<?php

namespace App\Modules\Core\Console\Commands\Observers;

use App\Modules\Core\Classes\CoreHelper;
use Illuminate\Console\Command;


class MakeObserver extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sn:make-observer {module} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It makes a observer class for the specified module.';

    protected $observerGenerator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ObserverGenerator $observerGenerator)
    {
        parent::__construct();
        $this->observerGenerator = $observerGenerator;
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
        $this->info(" ...... Creating Observer in {$module} .......");
        $this->info("=============================================");

        $module_path = app_path() . '/Modules/'.$module;
        $model_path = $module_path.'/Observers';

        if(!(CoreHelper::folder_exist($module_path))){
            $this->error("Module ".'('.$module.')'.' Not Found in App/Modules');
            die;
        }
        if(!(CoreHelper::folder_exist($model_path))){
            mkdir($module_path.'/Observers', 0777, true);
        }
        $this->observerGenerator->create($name,$model_path,$module);

        $this->info(" Observer Created in {$module} : {$name} ");
    }

}
