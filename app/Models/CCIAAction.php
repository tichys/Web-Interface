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
use Illuminate\Database\Eloquent\SoftDeletes;

class CCIAAction extends Model
{
    use SoftDeletes;

    protected $dates      = ['deleted_at','expires_at'];
    protected $connection = 'server';
    protected $table      = 'ccia_actions';
    protected $fillable   = ['type', 'issuedby', 'details', 'url'];
    protected $primaryKey = 'id';

    public function characters()
    {
        return $this->belongsToMany(ServerCharacter::class,'ccia_action_char','action_id','char_id');
    }
}
