<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZoomTrainingDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zoom_training_data', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("user_id")->default(0)->unsigned();
            $table->integer("training_id")->default(0)->unsigned();
            $table->string("join_time", 20)->default("");
            $table->string("leave_time", 20)->default("");
            $table->integer("duration")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zoom_training_data');
    }
}
