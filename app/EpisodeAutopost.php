<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EpisodeAutopost extends Model
{
    protected $fillable = ['episode_id','program_id', 'social'];

    public function episode()
    {
        return $this->belongsTo('App\Episode');
    }

}
