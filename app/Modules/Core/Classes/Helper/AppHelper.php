<?php

namespace App\Modules\Core\Classes\Helper;


use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class AppHelper
{

    public static function hasItems($original_array, $test_array)
    {
        $count_orginal = count($original_array);
        $difference_items = array_diff($original_array, $test_array);
        $count_new = count($difference_items);
        return (($count_orginal == $count_new) ? false : true);
    }

    public static function includeAsJsString($template,$id=null)
    {
        // reference : https://stackoverflow.com/questions/37362222/laravel-blade-include-into-a-javascript-variable
        $string = view($template);
        if (isset($id)){
            $string = view($template,['id'=>$id]);
        }
        return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\")));
    }

    public static function explodeString($delimiter,$string){
        if(gettype($string) != "array"){
            return explode($delimiter,$string);
        }
        return $string;
    }

    public static function formatString($string)
    {
        return ucfirst($string);
    }

    public static function replaceString($delimiter,$with,$string){
        return str_replace($delimiter,$with,$string);
    }

    public static function mimeValidation($ext , array $accepted_mimes){
        if(!in_array($ext, $accepted_mimes) ) {return false;}
        return true;
    }

    public static function getLatestItem(Model $model){
        return $model->latest('id')->first();
    }

    public static function createdBy(Model $model){
        return User::where('id',$model->added_by)->pluck('name')->first();
    }

    public static function updatedBy(Model $model){
        if(!is_null($model->updated_by)){
            return User::where('id',$model->updated_by)->pluck('name')->first();
        }
        return 'No User has Updated It';

    }





}