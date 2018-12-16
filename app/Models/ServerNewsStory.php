<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HTMLPurifier;

class ServerNewsStory extends Model
{
    use SoftDeletes;
    protected $connection = 'server';
    protected $table = 'news_stories';
    protected $primaryKey = 'id';
    public $timestamps = TRUE;

    private $purifier;

    function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed','p, ul, ol, li, h1, h2, h3, h4, h5, h6, br, strong, em, b, i ,u'); //No a[href]
        $config->set('AutoFormat.RemoveEmpty', true);
        $this->purifier = new HTMLPurifier($config);
    }

    public function channel(){
        return $this->belongsTo('App\Models\ServerNewsChannel','channel_id');
    }

    public function setAuthorAttribute($value){
        $this->attributes['author'] = $this->purifier->purify($value);
    }

    public function setBodyAttribute($value){
        $this->attributes['body'] = $this->purifier->purify($value);
    }

    public function setMessageTypeAttribute($value){
        $this->attributes['message_type'] = $this->purifier->purify($value);
    }
}
