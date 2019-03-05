<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{

    protected $fillable = ['user_id', 'episode_id'];

    protected $count;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }

}
