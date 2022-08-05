<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMostPopularProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('most_popular_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('warehouse_code');
            $table->string('product_code');
            $table->double('total_amount');
            $table->timestamps();

            $table->foreign('warehouse_code')->references('warehouse_code')->on('warehouses');
            $table->foreign('product_code')->references('product_code')->on('products_master');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('most_popular_products');
    }
}
