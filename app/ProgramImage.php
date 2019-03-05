<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cache;

class ProgramImage extends Model
{
    protected $fillable = ['program_id', 'small', 'medium', 'large'];


    public function program()
    {
        return $this->belongsTo('App\Program');
    }

    public function getSmallAttribute()
	{
        if ($this->attributes['small']==null) $this->attributes['small'] = "default.jpg";
    	return env('S3_PUBLIC_IMAGES_PROGRAMS').$this->attributes['small'];
	}

	public function getMediumAttribute()
	{
        if ($this->attributes['small']==null) $this->attributes['small'] = "default.jpg";
    	return env('S3_PUBLIC_IMAGES_PROGRAMS').$this->attributes['small'];
	}

	public function getLargeAttribute()
	{
        if ($this->attributes['small']==null) $this->attributes['small'] = "default.jpg";
    	return env('S3_PUBLIC_IMAGES_PROGRAMS').$this->attributes['small'];
	}


}