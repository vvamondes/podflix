<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Validator;
use Auth;


class Program extends Model
{
    use Sluggable;

    protected $fillable = ['id','name', 'slug', 'display_name', 'description', 'feed', 'episodes_count'
        , 'subscribed_count', 'subscribed'];

    protected $subscribed = false;

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function episodes()
    {
        return $this->hasMany('App\Episode');
    }

    public function logo()
    {
        return $this->hasOne('App\ProgramImage');
    }

    public function twitters()
    {
        return $this->hasMany('App\ProgramTwitter');
    }

    public function facebooks()
    {
        return $this->hasMany('App\ProgramFacebook');
    }

    public function googleplus()
    {
        return $this->hasMany('App\ProgramGoogleplus');
    }

    public function emails()
    {
        return $this->hasMany('App\ProgramEmail');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'separator' => '',
                'unique'          => false,
                'uniqueSuffix'    => null,
            ]
        ];
    }


    /*
     *
     * VALIDATOR
     *
     */
    private $rules = array(
        "name" => "required|unique:programs",
        "feed" => "required|unique:programs",
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
            Log::debug($data['name'] . " [Validator fails] " . $this->errors);
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
