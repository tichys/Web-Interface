<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SyndieContract extends Model
{
    use SoftDeletes;

    protected $connection = 'server';
    protected $table = 'syndie_contracts';
    protected $fillable = ['contractee_name','title','description','reward_credits','reward_other'];
    protected $primaryKey = 'contract_id';
    protected $dates = ['deleted_at'];
}
