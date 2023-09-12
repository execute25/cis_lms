<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultValuesToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->default("")->change();
            $table->string('email')->default("")->change();
            $table->string('password')->default("")->change();
            $table->boolean('department')->default(0)->change();
            $table->string('korean_name', 50)->default("")->change();
            $table->string('telegram_nickname', 50)->default("")->change();
            $table->string('phone', 15)->default("")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
