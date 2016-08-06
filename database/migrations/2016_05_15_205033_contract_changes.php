<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContractChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Create Objectives Table */
        Schema::connection('server')->create('syndie_contracts_objectives', function (Blueprint $table) {
            $table->increments('objective_id');
            $table->integer('contract_id'); // ID of the person offering the contract
            $table->string('status');
            /* Status Codes:
             * open -> The objective is uncompleted
             * closed -> The objective is completed
             */
            $table->string('title');
            $table->text('description');
            $table->integer('reward_credits')->nullable();
            $table->integer('reward_credits_update')->nullable();
            $table->text('reward_other')->nullable();
            $table->text('reward_other_update')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        /* Edit Comments Table */
        Schema::connection('server')->table('syndie_contracts_comments',function($table){
            $table->enum('report_status',['waiting-approval','accepted','rejected'])->nullable();
            /* Status Codes:
             * NULL -> Just a Comment not a completion report
             * waiting-approval -> Waiting for the author to confirm the completion of the contract
             * accepted -> Contract Completion has been accepted
             * rejected -> Contract Completion has been rejected
             */
            $table->foreign('contract_id','contract_id')
                ->references('id')
                ->on('syndie_contracts')
                ->onDelete('cascade');
        });

        /* Add Many-To-Many Comment <-> Completers */
        Schema::connection('server')->create('syndie_contracts_comments_completers', function(Blueprint $table)
        {
            $table->integer('user_id');
            $table->integer('comment_id')->unsigned();

            $table->foreign('user_id','user_id')
                ->references('id')
                ->on('player')
                ->onDelete('cascade');

            $table->foreign('comment_id','comment_id')
                ->references('comment_id')
                ->on('syndie_contracts_comments')
                ->onDelete('cascade');

            $table->primary(['user_id','comment_id'],'user_id_comment_id');
        });

        /* Add Many-To-Many Comment <-> Objectives */
        Schema::connection('server')->create('syndie_contracts_comments_objectives', function(Blueprint $table) {
            $table->integer('objective_id')->unsigned();
            $table->integer('comment_id')->unsigned();

            $table->foreign('objective_id','objectives_objective_id')
                ->references('objective_id')
                ->on('syndie_contracts_objectives')
                ->onDelete('cascade');

            $table->foreign('comment_id','comments_comment_id')
                ->references('comment_id')
                ->on('syndie_contracts_comments')
                ->onDelete('cascade');

            $table->primary(['objective_id','comment_id'],'objective_id_comment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /* Drop Many-To-Many Tables*/
        Schema::connection('server')->drop('syndie_contracts_comments_objectives');
        Schema::connection('server')->drop('syndie_contracts_comments_completers');

        /* Revert Comment Table Changes */
        Schema::connection('server')->table('syndie_contracts_comments',function($table){
            $table->dropColumn('report_status');
        });

        /* Drop Objectives Table */
        Schema::connection('server')->drop('syndie_contracts_objectives');
    }
}
