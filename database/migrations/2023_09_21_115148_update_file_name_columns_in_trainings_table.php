<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFileNameColumnsInTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->string('file_1_name',150)->default("")->change();
            $table->string('file_2_name',150)->default("")->change();
            $table->string('file_3_name',150)->default("")->change();
            $table->string('file_4_name',150)->default("")->change();
            $table->string('file_5_name',150)->default("")->change();
            $table->string('file_6_name',150)->default("")->change();
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
            $table->string('file_1_name',50)->default("")->change();
            $table->string('file_2_name',50)->default("")->change();
            $table->string('file_3_name',50)->default("")->change();
            $table->string('file_4_name',50)->default("")->change();
            $table->string('file_5_name',50)->default("")->change();
            $table->string('file_6_name',50)->default("")->change();
        });
    }
}
