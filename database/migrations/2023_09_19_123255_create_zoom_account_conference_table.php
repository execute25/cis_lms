<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZoomAccountConferenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('zoom_account_conference')) {
            Schema::create('zoom_account_conference', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->integer('conference_id')->unsigned()->default(0);
                $table->string('host_email', 100)->default("");
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
        Schema::table('zoom_account_conference', function (Blueprint $table) {
            Schema::dropIfExists('zoom_account_conference');
        });
    }
}
