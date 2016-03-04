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

namespace App\Services\Server;

use DB;
use App\Models\ServerPlayer;

class WhiteListStatus
{
    private $player_ckey = NULL;

    public function __construct($player_ckey)
    {
        $this->player_ckey = $player_ckey;
    }

    public function get_whitelist_array()
    {
        //Get Whitelist Status
        $statuses = DB::connection('server')->table('whitelist_statuses')->get();

        $status_list = array();
        foreach ($statuses as $whitelist) {
            $status_list[$whitelist->status_name] = $whitelist->flag;
        }

        $player = ServerPlayer::where('ckey', $this->player_ckey)->first();

        $whitelists = array();
        foreach ($status_list as $key => $value) {
            if (($player->whitelist_status & $value) != 0) {
                $whitelists[$key] = TRUE;
            } else {
                $whitelists[$key] = FALSE;
            }
        }

        return $whitelists;
    }
}