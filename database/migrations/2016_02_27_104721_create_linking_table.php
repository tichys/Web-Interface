<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('server')->create('player_linking', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('forum_id');
            $table->string('forum_username_short');
            $table->string('forum_username');
            $table->string('player_ckey');
            $table->enum('status',array('new','confirmed','rejected','linked'));
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('server')->drop('player_linking');
    }
}
