<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveStartAtFromTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn('end_at');
            $table->dropColumn('end_at_time');
            $table->dropColumn('start_at');
            $table->dropColumn('start_at_time');
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
            $table->string('end_at',20)->default("");
            $table->string('end_at_time',20)->default("");
            $table->string('start_at',20)->default("");
            $table->string('start_at_time',20)->default("");
        });
    }
}
