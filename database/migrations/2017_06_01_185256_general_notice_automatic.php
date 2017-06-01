<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GeneralNoticeAutomatic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('server')->table('ccia_general_notice_list', function (Blueprint $table) {
            $table->tinyInteger('automatic');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('server')->table('ccia_general_notice_list', function (Blueprint $table) {
            $table->dropColumn('automatic');
        });
    }
}
