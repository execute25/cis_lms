<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('cells')) {
            Schema::create('cells', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->string('name', 50);
                $table->integer('leader_id')->unsigned();
                $table->integer('dep_leader_id')->unsigned();
                $table->integer('region_id')->unsigned();
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
        Schema::table('cells', function (Blueprint $table) {
            Schema::dropIfExists('cells');
        });
    }
}
