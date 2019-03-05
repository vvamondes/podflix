<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Validator;

class Episode extends Model
{

    use Sluggable;

    protected $fillable = ['id','title', 'slug', 'display_name', 'description', 'file_url', 'file_type', 'file_length', 'duration', 'published_at',
        'played_count', 'liked_count', 'disliked_count', 'liked'];

    protected $liked = false;

    public function twitters()
    {
        return $this->hasMany('App\EpisodeTwitter');
    }

    public function autoposts()
    {
        return $this->hasMany('App\EpisodeAutopost');
    }

    public function program()
    {
        return $this->belongsTo('App\Program');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function players()
    {
        return $this->hasMany('App\Player');
    }

    public function likes()
    {
        return $this->hasMany('App\Like');
    }

    public function playlist()
    {
        return $this->hasMany('App\Playlist');
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }


    /*
     *
     * VALIDATOR
     *
     */
    private $rules = array(
        'title' => 'required|unique_with:episodes,program_id',
        'file_url' => ['required', 'regex:/^.*\.(mp3|ogg|m4a|aac)(\?|\z)/i'],
    );

    public function validate($data)
    {
        //dd($data);
        // make a new validator object
        $v = Validator::make($data, $this->rules);

        // check for failure
        if ($v->fails())
        {
            // set errors and return false
            $this->errors = $v->errors();
            //dd($v->errors());
            Log::debug($data['title'] . " " . $data['file_url'] . " [Validator fails] " . $this->errors);
            return false;
        }

        // validation pass
        return true;
    }

    protected $errors;

    public function errors()
    {
        return $this->errors;
    }


}
