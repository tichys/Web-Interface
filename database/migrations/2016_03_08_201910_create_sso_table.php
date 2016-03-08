<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSsoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('server')->create('web_sso', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ckey');
            $table->string('token');
            $table->string('ip');
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('server')->drop('web_sso');
    }
}
