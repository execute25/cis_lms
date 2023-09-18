<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileColumnsToTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->string('file_1', 150)->default("");
            $table->string('file_2', 150)->default("");
            $table->string('file_3', 150)->default("");
            $table->string('file_4', 150)->default("");
            $table->string('file_5', 150)->default("");
            $table->string('file_6', 150)->default("");
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
            $table->dropColumn('file_1');
            $table->dropColumn('file_2');
            $table->dropColumn('file_3');
            $table->dropColumn('file_4');
            $table->dropColumn('file_5');
            $table->dropColumn('file_6');
        });
    }
}
