<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProgramRequest extends Model
{
    //protected $fillable = ['user_id', 'program_id', 'liked'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
