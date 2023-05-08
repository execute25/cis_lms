<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('trainings')) {
            Schema::create('trainings', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->string('name',100);
                $table->string('start_at',20);
                $table->string('end_at',20);
                $table->string('zoom_conference_id',35);
                $table->string('bunny_id',200);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trainings', function (Blueprint $table) {
            Schema::dropIfExists('trainings');
        });
    }
}
