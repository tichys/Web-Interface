<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyndieContract extends Model
{
    protected $connection = 'server';
    protected $table = 'syndie_contracts';
    protected $fillable = ['contractee_name','title','description','reward_credits','reward_other'];
    protected $primaryKey = 'contract_id';
}
