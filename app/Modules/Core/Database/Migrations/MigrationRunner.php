<?php
/**
 * Created by PhpStorm.
 * User: shramik
 * Date: 3/27/18
 * Time: 12:22 PM
 */

namespace App\Modules\Core\Database\Migrations;

use Illuminate\Database\Migrations\Migrator;

class MigrationRunner extends Migrator
{

    public function rollback($paths = [], array $options = [])
    {
        return parent::rollback($paths, $options);
    }
}