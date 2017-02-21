<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->string('sku', 20);
            $table->string('brand', 30)->nullable();
            $table->string('description', 35)->nullable();
            $table->string('size', 30)->nullable();
            $table->string('weight', 30)->nullable();
            $table->string('barcode', 30)->nullable();
            $table->integer('masterpackquantity')->nullable();
            $table->string('taxcode', 30)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('msrp', 10, 2)->nullable();
            $table->integer('minimumorderquantity')->nullable();
            $table->primary('sku');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
