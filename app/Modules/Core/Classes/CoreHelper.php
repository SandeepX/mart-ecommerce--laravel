<?php
/**
 * Created by PhpStorm.
 * User: shramik
 * Date: 3/25/18
 * Time: 9:53 PM
 */

namespace App\Modules\Core\Classes;


use Illuminate\Support\Arr;
use Symfony\Component\Finder\Finder;
use File;


class CoreHelper
{


    public static function loadHelperClasses(){
        $finder = (new Finder())->files()->in((__DIR__));
        $class = new \ReflectionClass(self::class);
        $file_info = [];
        foreach ($finder as $file) {
            $relative_path = $file->getRelativePath();
            $class_name = basename($file, ".php");
            $name_space = $class->getNamespaceName().("\\".str_replace('/',"\\",$relative_path));
            if($relative_path != '') {
                $file_info [] = ([
                    'class' => $class_name,
                    'bind_on' => strtolower($class_name),
                    'full_class' => $name_space.'\\'.$class_name
                ]);
            }
        }

        return $file_info;
    }


    public static function serviceProviders()
    {
       $packages = ['App\Modules\\ModuleServiceProvider'];
        //$packages = [];
        $path = glob(app_path() . '/Modules/*');
        $modules = self::moduleDirectories($path);
        foreach ($modules as $key => $value)
        {
            $provider_path = app_path() ."/Modules/$value/Providers";
            if(self::folder_exist($provider_path) && !(self::isDirEmpty($provider_path)) ){
                $packages[] = self::getProviderNameSpace($provider_path,$value);
            }
        }
        return Arr::flatten($packages);
    }

    public static function helperAliases()
    {
        $aliases = [];
        foreach (self::loadHelperClasses() as $class){
            $aliases[$class['class']] = sprintf($class['full_class'],null,null);
        }
        return $aliases;
    }


    public static function moduleDirectories($path)
    {
        $directory_names = [];

        foreach ($path as $directory_path) {
           if(is_dir($directory_path)){
               $name = basename($directory_path);
               $directory_names[$name] = $name;
           }
        }
        return $directory_names;
    }

    public static function folder_exist($folder)
    {
        $path = realpath($folder);
        if($path !== false AND is_dir($path))
        {
            return $path;
        }
        return false;
    }

    public static function isDirEmpty($dir){
        $path = realpath($dir);
        $dir_state = (count(glob("$path/*")) === 0) ? true : false; // true for empty
        return $dir_state;
    }

    public static function getProviderNameSpace($path,$module){
         $finderFiles = Finder::create()->files()->in($path)->name('*.php');
         $filenames = array();
         $namespace = 'App\Modules'."\\".$module;
       foreach ($finderFiles as $finderFile) {
          $filenames[] = sprintf($namespace.'\\'.'Providers'."\\".basename($finderFile, ".php"),null,null);
         }
      return $filenames;
   }

    public static function getLatestBackupFile($path){

        $finderFiles = Finder::create()->files()->in($path)->name('*.zip');
        foreach ($finderFiles as $finderFile) {
          if(basename($finderFile) === self::getLatestFile($path)){
              return realpath($finderFile);
          };
        }
    }

    public static function getLatestFile($path){
        $files = scandir($path, SCANDIR_SORT_DESCENDING);
        $newest_file = $files[0];
        return $newest_file;
    }





}