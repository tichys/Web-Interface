<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServerNewsStory extends Model
{
    use SoftDeletes;
    protected $connection = 'server';
    protected $table = 'news_stories';
    protected $primaryKey = 'id';
    public $timestamps = TRUE;

    public function channel(){
        return $this->belongsTo('App\Models\ServerNewsChannel','channel_id');
    }
}
