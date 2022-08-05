<?php
/**
 * Created by PhpStorm.
 * User: shramik
 * Date: 3/27/18
 * Time: 10:46 AM
 */

namespace App\Modules\Core\Database\Seeds;
use Illuminate\Support\Str;


/**
 * Class SeedMaker
 * @package App\Modules\Core\Database\Seeds
 */
class SeedMaker
{
    /**
     * @return string
     */
    public function getStubPath()
    {
        return app_path().'/Modules/Core/Console/Commands/Seed/stubs';
    }

    /**
     * Create a new seeder at the given path.
     *
     * @param  string  $name
     * @param  string  $path
     * @return string
     * @throws \Exception
     */
    public function create($name, $path)
    {
        $stub = $this->getStub();
        file_put_contents($this->getPath($name,$path),$this->populateStub($name, $stub));
        return $path;
    }


    /**
     * Get the full path to the seeder.
     *
     * @param  string  $name
     * @param  string  $path
     * @return string
     */
    protected function getPath($name, $path)
    {
        return $path.'/'.$this->getClassName($name).'.php';
    }

    /**
     * Get the seeder stub file.
     *
     * @param  string  $table
     * @param  bool    $create
     * @return string
     */
    public function getStub()
    {
       return file_get_contents($this->getStubPath().'/seeder.stub');
    }


    /**
     * Populate the place-holders in the seeder stub.
     *
     * @param  string  $name
     * @param  string  $stub
     * @param  string  $table
     * @return string
     */
    protected function populateStub($name, $stub)
    {
        $stub = str_replace('DummyClass', $this->getClassName($name), $stub);
        return $stub;
    }

    /**
     * Get the class name of a seeder name.
     *
     * @param  string  $name
     * @return string
     */
    public function getClassName($name)
    {
        return Str::studly($name);
    }


}