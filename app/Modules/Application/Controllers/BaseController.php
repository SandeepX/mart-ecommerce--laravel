<?php

namespace App\Modules\Application\Controllers;

use App\Http\Controllers\Controller;
use View;

class BaseController extends Controller
{
    protected function loadViewData($path){

        View::composer($path,function ($view){
            $view->with('title', $this->title);
            $view->with('base_route', $this->base_route);
            $view->with('module', $this->module);
        });

        return $path;
    }
}
