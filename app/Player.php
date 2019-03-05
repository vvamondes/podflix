<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = ['episode_id','event_id', 'ip'];

    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }

}
