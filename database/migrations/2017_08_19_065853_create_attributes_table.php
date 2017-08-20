<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('productattributes', function (Blueprint $table) {
          $table->string('sku', 20);
          $table->boolean('Dairy Free')->nullable();
          $table->boolean('Gluten Free')->nullable();
          $table->boolean('Organic')->nullable();
          $table->boolean('Raw')->nullable();
          $table->boolean('Vegan')->nullable();
          $table->boolean('Vegetarian')->nullable();
          $table->boolean('Wheat Free')->nullable();
          $table->boolean('Sugar Free')->nullable();
          $table->boolean('Fair Trade')->nullable();
          $table->boolean('Produce Of GB')->nullable();
          $table->boolean('Nut Free')->nullable();
          $table->boolean('Egg Free')->nullable();
          $table->boolean('Celery Free')->nullable();
          $table->boolean('Lupin Free')->nullable();
          $table->boolean('Milk Free')->nullable();
          $table->boolean('Mustard Free')->nullable();
          $table->boolean('Sesame Seed Free')->nullable();
          $table->boolean('Soybean Free')->nullable();
          $table->boolean('Sulphur Dioxide Free')->nullable();
          $table->boolean('Maize Free')->nullable();
          $table->boolean('Citric Acid Free')->nullable();
          $table->boolean('Aluminium Free')->nullable();
          $table->boolean('Paraben Free')->nullable();
          $table->boolean('Crustacean Free')->nullable();
          $table->boolean('Mollusc Free')->nullable();
          $table->boolean('Fish Free')->nullable();
          $table->boolean('Peanut Free')->nullable();
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
        Schema::dropIfExists('productattributes');
    }
}
