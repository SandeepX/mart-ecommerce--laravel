<?php
/**
 * Created by PhpStorm.
 * User: shramik
 * Date: 3/26/18
 * Time: 1:05 PM
 */

namespace App\Modules\Core\Providers;




use App\Modules\Core\Console\Commands\Controller\MakeController;
//use App\Modules\Core\Console\Commands\Repository\MakeRepository;
//use App\Modules\Core\Console\Commands\MakeCSR;
use App\Modules\Core\Console\Commands\MakeModule;
use App\Modules\Core\Console\Commands\MakeModuleMigration;
use App\Modules\Core\Console\Commands\MigrateAll;
use App\Modules\Core\Console\Commands\MigrateModule;
use App\Modules\Core\Console\Commands\MigrationRollback;
use App\Modules\Core\Console\Commands\MigrationStatus;
use App\Modules\Core\Console\Commands\Models\MakeModel;
use App\Modules\Core\Console\Commands\ModuleCommands\ModuleCommand;
use App\Modules\Core\Console\Commands\Observers\MakeObserver;
use App\Modules\Core\Console\Commands\Providers\MakeProvider;
use App\Modules\Core\Console\Commands\Seed\MakeSeed;
use App\Modules\Core\Console\Commands\Seed\RunSeed;
use App\Modules\Core\Console\Commands\Service\MakeService;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends  ServiceProvider
{


    /**
     * The Custom Artisan Commands for the  application.
     *
     * @var array
     */
    protected $commands = [
        MakeModuleMigration::class,
        MigrateModule::class,
        MigrateAll::class,
        MigrationRollback::class,
        MigrationStatus::class,
        MakeSeed::class,RunSeed::class,
        ModuleCommand::class,
        MakeModel::class,
        MakeProvider::class,
        MakeController::class,
        MakeModule::class,
        MakeService::class,
        //MakeRepository::class,
       // MakeCSR::class,
        MakeObserver::class
    ];


    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->register_includes();

    }

    public function register_includes(){
        $this->commands($this->commands);
    }
}
