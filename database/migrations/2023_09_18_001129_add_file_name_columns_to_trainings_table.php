<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileNameColumnsToTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->string('file_1_name', 50)->default("");
            $table->string('file_2_name', 50)->default("");
            $table->string('file_3_name', 50)->default("");
            $table->string('file_4_name', 50)->default("");
            $table->string('file_5_name', 50)->default("");
            $table->string('file_6_name', 50)->default("");
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
            $table->dropColumn('file_1_name');
            $table->dropColumn('file_2_name');
            $table->dropColumn('file_3_name');
            $table->dropColumn('file_4_name');
            $table->dropColumn('file_5_name');
            $table->dropColumn('file_6_name');
        });
    }
}
