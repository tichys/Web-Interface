<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGitTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('server')->create('git_pull_requests', function (Blueprint $table) {
            $table->increments('pull_id')->unsigned();
            $table->string('title');
            $table->text('body');
            $table->integer('git_id')->unsigned();
            $table->string('merged_into');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('server')->create('git_pull_todos', function (Blueprint $table) {
            $table->increments('todo_id')->unsigned();
            $table->integer('pull_id')->unsigned();
            $table->integer('number');
            $table->text('description');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('pull_id')
                ->references('pull_id')
                ->on('git_pull_requests')
                ->onDelete('cascade');
        });

        Schema::connection('server')->create('git_pull_stats', function (Blueprint $table) {
            $table->increments('todo_stat_id')->unsigned();
            $table->integer('todo_id')->unsigned();
            $table->string('ckey');
            $table->string('status');
            $table->text('description');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('todo_id')
                ->references('todo_id')
                ->on('git_pull_todos')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('server')->drop('git_pull_requests');
        Schema::connection('server')->drop('git_pull_todos');
        Schema::connection('server')->drop('git_pull_stats');
    }
}
