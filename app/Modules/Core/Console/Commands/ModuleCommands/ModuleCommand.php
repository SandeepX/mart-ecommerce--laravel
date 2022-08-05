<?php
/**
 * Created by PhpStorm.
 * User: shramik
 * Date: 3/29/18
 * Time: 3:20 PM
 */

namespace App\Modules\Core\Console\Commands\ModuleCommands;


use App\Modules\Core\Classes\CoreHelper;
use Illuminate\Console\Command;

/**
 * Class ModuleCommand
 * @package App\Modules\Core\Console\Commands\ModuleCommands
 */
class ModuleCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sn:make-command {module} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It makes an artisan command for the specified module';


    /**
     * @var
     */
    protected $commandGenerator;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CommandGenerator $commandGenerator)
    {
        parent::__construct();
        $this->commandGenerator = $commandGenerator;
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
        $this->info(" ...... Creating Command in {$module} .......");
        $this->info("=============================================");

        $module_path = app_path() . '/Modules/'.$module;
        $console_path = $module_path.'/Console';
        $command_path = $console_path.'/Commands';

        if(!(CoreHelper::folder_exist($module_path))){
            $this->error("Module ".'('.$module.')'.' Not Found in App/Modules');
            die;
        }
        if(!(CoreHelper::folder_exist($command_path))){
            mkdir($console_path.'/Commands', 0777, true);
        }
       $this->commandGenerator->create($name,$command_path,$module);

        $this->info("Command Created in {$module} : {$name} ");
    }

}