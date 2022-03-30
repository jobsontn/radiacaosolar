<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRadiacaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radiacaos', function (Blueprint $table) {
            $table->id();
            $table->decimal('longitude', 14, 10);
            $table->decimal('latitude', 14 , 10);
            $table->integer('00_ANNUAL');
            $table->integer('01_JAN');
            $table->integer('02_FEB');
            $table->integer('03_MAR');
            $table->integer('04_APR');
            $table->integer('05_MAY');
            $table->integer('06_JUN');
            $table->integer('07_JUL');
            $table->integer('08_AUG');
            $table->integer('09_SEP');
            $table->integer('10_OCT');
            $table->integer('11_NOV');
            $table->integer('12_DEZ');
            $table->decimal('longitude2', 14 , 10)->nullable();
            $table->decimal('latitude2', 14 , 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('radiacaos');
    }
}
