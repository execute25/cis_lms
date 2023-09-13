<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWatchTimeColumnToTrainingUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_users', function (Blueprint $table) {
            $table->string('attend_duration',20)->default("")
                ->comment("attendance duration on zoom conference");
            $table->string('video_duration',20)->default("")->comment("bunny video show duration");
            $table->string('watch_start_at',20)->default("");
            $table->string('progress',40)->default("")->comment("bunny video show duration in percent");
            $table->string('watch_time',20)->default("");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_users', function (Blueprint $table) {
            $table->dropColumn('attend_duration');
            $table->dropColumn('video_duration');
            $table->dropColumn('watch_start_at');
            $table->dropColumn('progress');
            $table->dropColumn('watch_time');
        });
    }
}
