<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductExtendedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productsextended', function (Blueprint $table) {
            $table->string('sku', 20);
            $table->text('Long_Description')->nullable();
            $table->text('Web_Description')->nullable();
            $table->string('Department', 50)->nullable();
            $table->string('Storage', 30)->nullable();
            $table->boolean('AllowAmbientShipping')->nullable();
            $table->boolean('Expiry_Date')->nullable();
            $table->string('Shelf_Life', 5)->nullable();
            $table->string('Guaranteed_Shelf_Life', 5)->nullable();
            $table->boolean('GSL_Product')->nullable();
            $table->boolean('Sell_Singles_Retail')->nullable();
            $table->string('Outer_Gross_Weight', 30)->nullable();
            $table->string('Outer_Barcode', 30)->nullable();
            $table->string('SingleUnit_Image_Url', 150)->nullable();
            $table->string('Outer_Image_Url', 150)->nullable();
            $table->string('Distance_Selling_PDF', 150)->nullable();
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
        Schema::dropIfExists('productsextended');
    }
}
