<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EpisodeTwitter extends Model
{
    protected $fillable = ['episode_id', 'name'];

    public function twitters()
    {
        return $this->belongsTo('App\Episodes');
    }
}
