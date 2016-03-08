<?php

/**
 * Copyright (c) 2016 'Werner Maisl'
 *
 * This file is part of Aurorastation-Wi
 * Aurorastation-Wi is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCciaactionlist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('server')->create('cciaactions', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type',array('injunction','suspension','warning'));
            $table->string('characters');
            $table->string('issuedby');
            $table->text('details');
            $table->string('url');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::connection('server')->create('cciaaction_player', function (Blueprint $table) {
            $table->integer('action_id')->unsigned();
            $table->integer('player_id');

            $table->foreign('action_id')
                ->references('id')
                ->on('cciaactions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('player_id')
                ->references('id')
                ->on('player')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->primary(['action_id','player_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('server')->drop('cciaaction_player');
        Schema::connection('server')->drop('cciaactions');
    }
}
