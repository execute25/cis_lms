<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingCategoryMembergroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('training_category_membergroup')) {
            Schema::create('training_category_membergroup', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->integer('training_category_id')->default(0)->unsigned();
                $table->integer('member_group_id')->default(0)->unsigned();
                $table->index(['training_category_id']);
                $table->index(['member_group_id']);
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
        Schema::table('training_category_membergroup', function (Blueprint $table) {
            Schema::dropIfExists('training_category_membergroup');
        });
    }
}
