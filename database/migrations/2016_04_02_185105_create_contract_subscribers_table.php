<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('server')->create('syndie_contracts_subscribers', function (Blueprint $table) {
            $table->integer('contract_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('contract_id')
                ->references('contract_id')
                ->on('syndie_contracts')
                ->onDelete('cascade');

            $table->primary(['contract_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('server')->drop('syndie_contracts_subscribers');
    }
}
