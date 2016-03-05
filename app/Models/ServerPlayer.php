<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

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
     * @param $flag_input
     *
     * @return bool|null
     */
    public function add_player_whitelist_flag($flag_input)
    {
        if ($this->ckey == NULL) return NULL;

        //Check if its a string or a arrray
        if (is_integer($flag_input)) {

            $this->whitelist_status |= $flag_input;
            $this->save();
            return TRUE;

        } elseif (is_array($flag_input)) {

            foreach ($flag_input as $flag_value) {
                $this->whitelist_status |= $flag_value;
            }
            $this->save();
            return TRUE;

        } elseif (is_string($flag_input)) {

            //Get Whitelist ID for Whitelist Name
            $whitelists = array_flip($this->get_available_whitelists());

            $whitelist_flag = $whitelists[$flag_input];

            $this->whitelist_status |= $whitelist_flag;
            $this->save();
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
     * @param $flag_input
     *
     * @return bool|null
     */
    public function strip_player_whitelist_flag($flag_input)
    {
        if ($this->ckey == NULL) return NULL;

        //Check if its a string or a arrray
        if (is_integer($flag_input)) {

            $this->whitelist_status -= $flag_input;
            $this->save();
            return TRUE;

        } elseif (is_array($flag_input)) {

            foreach ($flag_input as $flag_value) {
                $this->whitelist_status -= $flag_value;
            }
            $this->save();
            return TRUE;

        } elseif (is_string($flag_input)) {

            //Get Whitelist ID for Whitelist Name
            $whitelists = array_flip($this->get_available_whitelists());

            $whitelist_flag = $whitelists[$flag_input];

            $this->whitelist_status -= $whitelist_flag;
            $this->save();
            return TRUE;

        }else {
            return NULL;
        }
    }

    public function get_available_whitelists()
    {
        //Get Whitelist Status
        return DB::connection('server')->table('whitelist_statuses')->pluck('status_name', 'flag');
    }

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
