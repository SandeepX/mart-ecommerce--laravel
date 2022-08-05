<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TestCompositeUniqueVariantValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('variant_values', function (Blueprint $table) {
            //
            $table->dropUnique('slug');

            $table->unique(['variant_code','slug'],'uq_variant_code_slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('variant_values', function (Blueprint $table) {
            //
        });
    }
}
