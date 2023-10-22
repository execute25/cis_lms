<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateConferenceIdInZoomAccountConferenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zoom_account_conference', function (Blueprint $table) {
            $table->string('conference_id',35)->default("")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zoom_account_conference', function (Blueprint $table) {
            $table->integer('conference_id')->change();
        });
    }
}
