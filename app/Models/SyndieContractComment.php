<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SyndieContractComment extends Model
{
    use SoftDeletes;

    protected $connection = 'server';
    protected $table = 'syndie_contracts_comments';
    protected $fillable = ['contract_id', 'commentor_name', 'title', 'comment'];
    protected $primaryKey = 'comment_id';
    protected $dates = ['deleted_at'];
}
