<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumsToTrainingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->tinyInteger('is_use_zoom')->unsigned();
            $table->string('start_at_time');
            $table->string('include_groups');
            $table->text('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn('is_use_zoom');
            $table->dropColumn('start_at_time');
            $table->dropColumn('include_groups');
            $table->dropColumn('description');
        });
    }
}
