<?php
/**
 * Created by PhpStorm.
 * User: shramik
 * Date: 3/29/18
 * Time: 8:17 AM
 */

namespace App\Modules\Core\Database\Seeds;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class SeedRunner
{

  private $seederClass = null;

  public function setSeederClass ($seederClass){
      $this->seederClass = $seederClass;
  }

  public function getModuleSeederClasses($module)
  {

      $module_path = app_path() . '/Modules/'.$module;
      $database_path = $module_path.'/Database';
      $seed_path = $database_path.'/seeds';
      $files = array_diff( scandir($seed_path), array(".", "..") );
    //  dd(($files));
     // $finderFiles = Finder::create()->files()->in($seed_path)->name('*.php');
      $filenames = array();

      $seederNameSpace = "App\\Modules\\{$module}\\Database\\seeds\\";
      foreach ($files as $finderFile) {
          $filenames[] = $seederNameSpace.basename($finderFile, ".php");
      }
    //  dd($filenames);

      return $filenames;
  }




    public function moduleSeed($module)
    {
        $seeders = [];

        $moduleClasses = $this->getModuleSeederClasses($module);

        if (!$seederClass = $this->seederClass){
            foreach ($moduleClasses as $class) {
//                 $class = new \ReflectionClass($class);
//                dd($class);
//                 //dd(class_exists($));
//                if (class_exists($class)) {
//                    $seeders[] = $class;
//
//                }
                $seeders[] = $class;
            }
       }else{
            $className = $this->getSeederName($module);
            if(!class_exists($className)){
                throw new \Exception('No Such Class Exists in this module');
            }
            $seeders[] =  Str::finish(substr($className, 0, strrpos($className, '\\')), '\\') . $seederClass;
        }

        if (count($seeders) > 0) {
            array_walk($seeders, [$this, 'dbSeed']);
        }
    }

    /**
     * Seed the specified module.
     *
     * @param string $className
     */
    public function dbSeed($className)
    {


       $params = ['--class' => $className];
        //$params = ['--class' => $className];

        Artisan::call('db:seed',$params);
    }

    public function getSeederName($module)
    {
        $seederNameSpace = "App\\Modules\\{$module}\\Database\\seeds\\";
        return  $seederNameSpace.$this->seederClass;
    }


}
