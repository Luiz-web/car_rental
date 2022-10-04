<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_brand');
            $table->string('name', 30);
            $table->string('image', 100);
            $table->integer('number_doors');
            $table->integer('seats');
            $table->boolean('air_bag');
            $table->boolean('abs');
            $table->timestamps();
    
            //foreign key (constraints)
            $table->foreign('id_brand')->references('id')->on('brands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_models');
    }
}
