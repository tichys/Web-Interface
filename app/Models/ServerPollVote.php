<?php
/**
 * Copyright (c) 2018 "Werner Maisl"
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

class ServerPollVote extends Model
{
    public $timestamps = FALSE;
    protected $connection = 'server';
    protected $table = 'poll_vote';
    protected $fillable = ['datetime', 'pollid', 'optionid', 'ckey', 'ip', 'adminrank', 'rating'];
    protected $primaryKey = 'id';

    public function question()
    {
        return $this->belongsTo('App\Models\ServerPollQuestion', 'id', 'pollid');
    }

    public function option()
    {
        return $this->belongsTo('App\Models\ServerPollOption', 'id', 'optionid');
    }
}
