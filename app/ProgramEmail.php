<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramEmail extends Model
{
    protected $fillable = ['program_id', 'name'];

    public function program()
    {
        return $this->belongsTo('App\Program');
    }
}
