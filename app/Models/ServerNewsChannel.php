<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServerNewsChannel extends Model
{
    use SoftDeletes;
    protected $connection = 'server';
    protected $table = 'news_channels';
    protected $primaryKey = 'id';
    public $timestamps = TRUE;

    public function news(){
        return $this->hasMany('App\Models\ServerNewsStory','channel_id');
    }
}
