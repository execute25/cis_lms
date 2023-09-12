<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultValuesToFieldIfTraingnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->string('end_at', 20)->nullable(false)->change();
            $table->string('bunny_id')->nullable(false)->change();
            $table->string('include_groups', 1000)->nullable(false)->change();
            $table->string('zoom_conference_id', 35)->nullable(false)->change();
            $table->text('description')->default("")->nullable(false)->change();
            $table->boolean('is_use_zoom')->default(0)->nullable(false)->change();
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
            //
        });
    }
}
