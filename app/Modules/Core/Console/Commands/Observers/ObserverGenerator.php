<?php
/**
 * Created by PhpStorm.
 * User: shramik
 * Date: 3/29/18
 * Time: 10:24 PM
 */

namespace App\Modules\Core\Console\Commands\Observers;


use Illuminate\Support\Str;

class ObserverGenerator
{

    /**
     * @return string
     */
    public function getStubPath()
    {
        return app_path().'/Modules/Core/Console/Commands/Observers/stubs';
    }

    /**
     * Create a new model at the given path.
     *
     * @param  string  $name
     * @param  string  $path
     * @return string
     * @throws \Exception
     */
    public function create($name, $path,$module)
    {
        $stub = $this->getStub();
        file_put_contents($this->getPath($name,$path),$this->populateStub($name, $stub,$module));
        return $path;
    }


    /**
     * Get the full path to the model.
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
     * Get the model stub file.
     *
     * @param  string  $table
     * @param  bool    $create
     * @return string
     */
    public function getStub()
    {
        return file_get_contents($this->getStubPath().'/observer.stub');
    }


    /**
     * Populate the place-holders in the model stub.
     *
     * @param  string  $name
     * @param  string  $stub
     * @param  string  $table
     * @return string
     */
    protected function populateStub($name, $stub,$module)
    {
        $old = ['DummyClass','DummyNamespace'];
        $new = [$this->getClassName($name),"App\\Modules\\{$module}\\Observers"];
        $stub = str_replace($old, $new, $stub);
        return $stub;
    }

    /**
     * Get the class name of the model.
     *
     * @param  string  $name
     * @return string
     */
    public function getClassName($name)
    {
        return Str::studly($name);
    }
}