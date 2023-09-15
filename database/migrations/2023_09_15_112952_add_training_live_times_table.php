<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrainingLiveTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('training_live_times')) {
            Schema::create('training_live_times', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->integer('training_id')->default(0)->unsigned();
                $table->string('start_at',100)->default("");
                $table->string('end_at',100)->default("");
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
        Schema::table('training_live_times', function (Blueprint $table) {
            Schema::dropIfExists('training_live_times');
        });
    }
}
