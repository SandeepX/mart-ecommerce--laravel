<?php

namespace App\Modules\Core\Console\Commands\Providers;

use App\Modules\Core\Classes\CoreHelper;
use Illuminate\Console\Command;


class MakeProvider extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sn:make-provider {module} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It makes a service provider for the specified module.';

    protected $providerGenerator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ProviderGenerator $providerGenerator)
    {
        parent::__construct();
        $this->providerGenerator = $providerGenerator;
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
        $this->info(" ...... Creating Service Provider in {$module} .......");
        $this->info("=============================================");

        $module_path = app_path() . '/Modules/'.$module;
        $model_path = $module_path.'/Providers';

        if(!(CoreHelper::folder_exist($module_path))){
            $this->error("Module ".'('.$module.')'.' Not Found in App/Modules');
            die;
        }
        if(!(CoreHelper::folder_exist($model_path))){
            mkdir($module_path.'/Providers', 0777, true);
        }
        $this->providerGenerator->create($name,$model_path,$module);

        $this->info("Service Provider Created in {$module} : {$name} ");
    }

}
