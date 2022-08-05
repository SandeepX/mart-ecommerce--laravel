<?php

namespace App\Modules\Core\Classes\Helper;


class ViewRender
{
   public static function priorityColor($priority_key){
       $priorities = ['low'=>'info','medium'=>'warning','high'=>'danger'];
       return $priorities[$priority_key];
   }
   public static function statusColor($status_key){
       $statuses = [
           'new'=>'primary','on-hold-migrations'=>'warning',
           'on-going'=>'info','cancelled'=>'danger',
           'completed' => 'success'
       ];
       return $statuses[$status_key];
   }

   public static function taskLabels(){
       return ['to-do','on-going','completed','on-hold-migrations','cancelled'];
   }

    public static function labelTaskIcons(){
        $labels = [
            'icons' =>['to-do'=>'fa-thumb-tack','on-going'=>'fa-cogs', 'completed' => 'fa-check-circle-o','on-hold-migrations'=>'fa-thumb-tack','cancelled'=>'fa-cogs'],
            'colors' =>['to-do'=>'deepskyblue','on-going'=>'blueviolet', 'completed' => 'green','on-hold-migrations'=>'orangered','cancelled'=>'red'],
            'priority_icons' =>['high'=>'orangered','medium'=>'blue', 'low' => 'grey'],
        ];
        return $labels;
    }
}