<?php
/**
 * Copyright (c) 2016 "Werner Maisl"
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
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class ServerPlayer extends Model
{
    protected $connection = 'server';
    protected $table = 'player';
    protected $fillable = ['ckey', 'ip', 'lastadminrank', 'whitelist_status'];
    protected $primaryKey = 'id';
    protected $dates = ['firstseen', 'lastseen'];
    public $timestamps = FALSE;

    /**
     * Adds flags to a player
     *
     * Accepts either a string with the flag value or a array with the falg values as parameter
     * The player_ckey needs to be set
     *
     * The adminname needs to be set or the admin needs to be logged in
     *
     * @param $flag_input
     * @param $adminname
     *
     * @return bool|null
     */
    public function add_player_whitelist_flag($flag_input, $adminname)
    {
        if ($this->ckey == NULL) return NULL;


        //Check if its a string or a arrray
        if (is_integer($flag_input)) {

            $this->whitelist_status |= $flag_input;
            $this->save();

            //Write Log
            DB::connection('server')->table('whitelist_log')->insert(
                [
                    'datetime' => date("Y-m-d H:i:s", time()),
                    'user' => $adminname,
                    'action_method' => 'Website2',
                    'action' => 'Added whitelistflag: '.$flag_input.' to player: '.$this->ckey,
                ]
            );

            return TRUE;

        } elseif (is_array($flag_input)) {

            foreach ($flag_input as $flag_value) {
                $this->whitelist_status |= $flag_value;
            }
            $this->save();

            //Write Log
            DB::connection('server')->table('whitelist_log')->insert(
                [
                    'datetime' => date("Y-m-d H:i:s", time()),
                    'user' => $adminname,
                    'action_method' => 'Website2',
                    'action' => 'Added whitelist flags: '.impode(";",$flag_input).' to player: '.$this->ckey,
                ]
            );

            return TRUE;

        } elseif (is_string($flag_input)) {

            //Get Whitelist ID for Whitelist Name
            $whitelists = array_flip($this->get_available_whitelists());

            $whitelist_flag = $whitelists[$flag_input];

            $this->whitelist_status |= $whitelist_flag;
            $this->save();

            //Write Log
            DB::connection('server')->table('whitelist_log')->insert(
                [
                    'datetime' => date("Y-m-d H:i:s", time()),
                    'user' => $adminname,
                    'action_method' => 'Website2',
                    'action' => 'Added whitelist: '.$flag_input.' to player: '.$this->ckey,
                ]
            );

            return TRUE;

        } else {
            return NULL;
        }
    }

    /**
     * Removes flags from a player
     *
     * Accepts either a string with the flag value or a array with the falg values as parameter
     * The player_ckey needs to be set
     *
     * The adminname needs to be set or the admin needs to be logged in
     *
     * @param $flag_input
     * @param $adminname
     *
     * @return bool|null
     */
    public function strip_player_whitelist_flag($flag_input, $adminname)
    {
        if ($this->ckey == NULL) return NULL;

        //Check if its a string or a arrray
        if (is_integer($flag_input)) {

            $this->whitelist_status -= $flag_input;
            $this->save();

            //Write Log
            DB::connection('server')->table('whitelist_log')->insert(
                [
                    'datetime' => date("Y-m-d H:i:s", time()),
                    'user' => $adminname,
                    'action_method' => 'Website2',
                    'action' => 'Removed whitelistflag: '.$flag_input.' from player: '.$this->ckey,
                ]
            );

            return TRUE;

        } elseif (is_array($flag_input)) {

            foreach ($flag_input as $flag_value) {
                $this->whitelist_status -= $flag_value;
            }
            $this->save();

            //Write Log
            DB::connection('server')->table('whitelist_log')->insert(
                [
                    'datetime' => date("Y-m-d H:i:s", time()),
                    'user' => $adminname,
                    'action_method' => 'Website2',
                    'action' => 'Removed whitelist flags: '.impode(";",$flag_input).' from player: '.$this->ckey,
                ]
            );

            return TRUE;

        } elseif (is_string($flag_input)) {

            //Get Whitelist ID for Whitelist Name
            $whitelists = array_flip($this->get_available_whitelists());

            $whitelist_flag = $whitelists[$flag_input];

            $this->whitelist_status -= $whitelist_flag;
            $this->save();

            //Write Log
            DB::connection('server')->table('whitelist_log')->insert(
                [
                    'datetime' => date("Y-m-d H:i:s", time()),
                    'user' => $adminname,
                    'action_method' => 'Website2',
                    'action' => 'Remove whitelist: '.$flag_input.' from player: '.$this->ckey,
                ]
            );

            return TRUE;

        } else {
            return NULL;
        }
    }

    /**
     * Returns a array with all available whitelists (name and status flag)
     *
     * @return array
     */
    public function get_available_whitelists()
    {
        //Get Whitelist Status
        return DB::connection('server')->table('whitelist_statuses')->pluck('status_name', 'flag');
    }

    /**
     * Returns a array with all available whitelists and a true/false value for each one
     *
     * @return array|null
     */
    public function get_player_whitelists()
    {
        if ($this->ckey == NULL) return NULL;
        //Get Whitelist Status
        $statuses = DB::connection('server')->table('whitelist_statuses')->get();

        $status_list = array();
        foreach ($statuses as $whitelist) {
            $status_list[$whitelist->status_name] = $whitelist->flag;
        }

        $whitelists = array();
        foreach ($status_list as $key => $value) {
            if (($this->whitelist_status & $value) != 0) {
                $whitelists[$key] = TRUE;
            } else {
                $whitelists[$key] = FALSE;
            }
        }

        return $whitelists;
    }
}
