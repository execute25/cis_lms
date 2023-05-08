<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('training_users')) {
            Schema::create('training_users', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->integer('user_id')->unsigned();
                $table->integer('trainig_id')->unsigned();
                $table->string('join_time',100);
                $table->string('leave_time',100);
                $table->integer('duration')->unsigned();
                $table->index(['user_id']);
                $table->index(['trainig_id']);
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
        Schema::table('training_users', function (Blueprint $table) {
            Schema::dropIfExists('training_users');
        });
    }
}
