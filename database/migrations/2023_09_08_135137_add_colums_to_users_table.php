<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cust_prefix',10)->default("");
            $table->integer('cust_number')->default(0);
            $table->string('team',30)->default("");
            $table->integer('cell_id')->default(0)->unsigned();
            $table->tinyInteger('is_new_member')->default(0)->unsigned();
            $table->string('gender',10)->default("");
            $table->string('birth',20)->default("");
            $table->string('id_number',20)->default("");
            $table->string('member_type',20)->default("")->comment("집사,성도");
            $table->string('register_type',20)->default("")->comment("총회등록,입교,교회등록");
            $table->string('nationality',40)->default("");
            $table->string('country',40)->default("");
            $table->string('city',40)->default("");
            $table->tinyInteger('is_free_report')->default(0)->unsigned()->comment("0 - not, 1 출결제외");
            $table->string('free_report_reason',100)->default("")->comment("ex.신앙유약");
            $table->integer('center_user_id')->default(0)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('cust_prefix');
            $table->dropColumn('cust_prefix');
            $table->dropColumn('team');
            $table->dropColumn('cell_id');
            $table->dropColumn('is_new_member');
            $table->dropColumn('gender');
            $table->dropColumn('birth');
            $table->dropColumn('id_number');
            $table->dropColumn('member_type');
            $table->dropColumn('register_type');
            $table->dropColumn('nationality');
            $table->dropColumn('country');
            $table->dropColumn('city');
            $table->dropColumn('is_free_report');
            $table->dropColumn('free_report_reason');
            $table->dropColumn('center_user_id');
        });
    }
}
