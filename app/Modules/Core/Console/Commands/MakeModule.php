<?php

namespace App\Modules\Core\Console\Commands;

use Illuminate\Console\Command;


class MakeModule extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sn:make-module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It makes a new module';

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
        $this->info(" ...... Creating Module .......");
        $this->info("=============================================");


        $module_name = $this->argument('name');


        $base_module_path = app_path() . '/Modules';
        $new_module = $base_module_path.'/'.$module_name;

        $folder_to_make = [
                           'Config',
                           'Console'=>['Commands'],
                           'Controllers',
                           'Database'=>['factories','migrations','seeds'],
                           'Validation',
                           'Models',
                           'Providers',
                           'Repositories',
                           'Services',
                           'Views',
                           'Resources',
                           'Requests'
                          ];
        $route_file = $new_module.'/'.'routes.php';

        foreach ($folder_to_make as $key => $child_folder){
            if (is_array($child_folder)){
                $this->makeDir($new_module.'/'.$key);
                foreach ($child_folder as $child_key => $folder){
                    $this->makeDir($new_module.'/'.$key.'/'.$folder);
                }
            }else{
                $this->makeDir($new_module.'/'.$child_folder);
            }
        }
        if(!file_exists($route_file)){
            touch($route_file);
        }

        $this->info("New Module Created : {$module_name} ");
    }

    private function makeDir($folder)
    {
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
    }

}