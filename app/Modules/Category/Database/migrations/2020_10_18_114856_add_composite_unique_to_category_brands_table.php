<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompositeUniqueToCategoryBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_brand', function (Blueprint $table) {
            $table->unique(['category_code','brand_code'],'category_brand_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_brand', function (Blueprint $table) {
            $table->dropUnique('category_brand_unique');
        });
    }
}
