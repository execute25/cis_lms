<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCellUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('cell_user')) {
            Schema::create('cell_user', function (Blueprint $table) {
                $table->integer('user_id')->unsigned();
                $table->integer('cell_id')->unsigned();
                $table->index(['user_id']);
                $table->index(['cell_id']);
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
        Schema::table('cell_user', function (Blueprint $table) {
            Schema::dropIfExists('cell_user');
        });
    }
}
