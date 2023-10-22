<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZoomAccountSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('zoom_account_settings')) {
            Schema::create('zoom_account_settings', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->string('host_email',100)->default("");
                $table->string('zoom_account_id',100)->default("");
                $table->string('zoom_client_id',100)->default("");
                $table->string('zoom_client_secret',100)->default("");
                $table->string('zoom_redirect_url',200)->default("");
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
        Schema::table('zoom_account_settings', function (Blueprint $table) {
            Schema::dropIfExists('zoom_account_settings');
        });
    }
}
