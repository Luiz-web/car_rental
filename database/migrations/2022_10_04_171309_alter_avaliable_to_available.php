<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAvaliableToAvailable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->renameColumn('avaliable', 'available');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->renameColumn('available', 'avaliable');
        });
    }
}
