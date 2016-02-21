<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyndieContractComment extends Model
{
    protected $connection = 'server';
    protected $table = 'syndie_contracts_comments';
    protected $fillable = ['contract_id', 'commentor_name', 'title', 'comment'];
    protected $primaryKey = 'comment_id';
}
