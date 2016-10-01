<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('wi')->create('user_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name'); //Name of the User class
            $table->text('description')->nullable(); //Description of the User class
            $table->integer('web_role_id')->nullable(); //ID of the Web Role for the User class
            $table->integer('server_rank_flags')->nullable(); //Flags on the Server
            $table->string('server_rank_title')->nullable(); //Title for the Server Rank
            $table->integer('forum_group_id')->nullable(); //The numeric id of the forum group the user should be added to

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('wi')->drop('user_classes');
    }
}
