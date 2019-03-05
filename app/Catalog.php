<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Validator;

class Catalog extends Model
{
    use Sluggable;

    protected $fillable = ['id','name', 'slug', 'display_name', 'description', 'count'];


    public function categories() {
        return $this->belongsToMany('App\Category');
    }





    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }




    /*
     *
     * START VALIDATOR
     *
     */
    private $rules = array(
        "name" => "required|unique:catalogs",
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

    /*
     *
     * END VALIDATOR
     *
     */

}
