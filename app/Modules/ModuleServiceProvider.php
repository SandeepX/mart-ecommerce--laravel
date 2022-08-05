<?php

namespace App\Modules;

use Illuminate\Support\ServiceProvider;


class ModuleServiceProvider extends ServiceProvider
{


    public function loadConfig($configFolder, $namespace = null)
    {
        $files = $this->app['files']->files($configFolder);
        $namespace = $namespace ? $namespace . '::' : '';

        foreach($files as $file)
        {
            $config = $this->app['files']->getRequire($file);
            $name = $this->app['files']->name($file);

            // special case for files named config.php (config keyword is omitted)
            if($name === 'config')
            {
                foreach($config as $key => $value) $this->app['config']->set($namespace . $key , $value);
            }

            $this->app['config']->set($namespace . $name , $config);
        }
    }

    public function boot()
    {
        // For each of the registered modules, include their routes and Views
        $modules = config("module.modules");
        foreach($modules as $module){


            // Load the routes for each of the modules
            if(file_exists(__DIR__.'/'.$module.'/api.php')) {
                include __DIR__.'/'.$module.'/api.php';
            }

            // Load the routes for each of the modules
            if(file_exists(__DIR__.'/'.$module.'/routes.php')) {
                include __DIR__.'/'.$module.'/routes.php';
            }

            // Load the views
            if(is_dir(__DIR__.'/'.$module.'/Views')) {
                $this->loadViewsFrom(__DIR__.'/'.$module.'/Views', $module);
            }

            // Load the Config
            if(is_dir(__DIR__.'/'.$module.'/Config')) {
                $this->loadConfig(__DIR__.'/'.$module.'/Config');
            }

        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
