<?php
/**
 * Created by PhpStorm.
 * User: shramik
 * Date: 5/11/18
 * Time: 3:13 PM
 */

namespace App\Modules\User\Helpers;


use App\Modules\Core\Classes\Helper\AppHelper;
use App\Modules\User\Models\User;

class UserHelper
{

  public static function getExceptSuperAdminUsers()
  {
     $users = User::all();
     $except_spadmin_users = $users->reject(function($user){
             return $user->isSuperAdmin() == true;
     }) ;
    return $except_spadmin_users;
  }

  public static function getExceptAuthUser(){
      return User::where('id',auth()->user()->id)->get();
  }

  public static function getUsersFromId(array  $ids){
   return User::whereIn('id',$ids)->select('id','name')->get();
   }
}