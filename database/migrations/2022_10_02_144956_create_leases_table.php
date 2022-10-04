<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_customer');
            $table->unsignedBigInteger('id_car');
            $table->dateTime('start_date');
            $table->dateTime('end_date_expected');
            $table->dateTime('end_date_accomplished');
            $table->float('daily_value', 8,2);
            $table->integer('initial_km');
            $table->integer('final_km');
            $table->timestamps();
    
            //foreign key (constraints)
            $table->foreign('id_customer')->references('id')->on('customers');
            $table->foreign('id_car')->references('id')->on('cars');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leases');
    }
}
