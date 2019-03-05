<?php

namespace App\Http\Controllers;

use App\Playlist;
use App\User;
use App\Episode;

use Illuminate\Http\Request;

use Requests;
use Auth;
use Log;

class SubscriptionController extends Controller
{

    public function get(){

        $episodes = array();

        //Minhas Inscrições
        if (Auth::check())
        {
            $subscriptions = User::find(Auth::user()->id);
            $programsIdsArray = array();
            foreach ($subscriptions->subscriptions as $subscription) {
                $programsIdsArray[] = $subscription->program_id;
            }
            if(count($programsIdsArray)>0) {
                $episodes = Episode::whereIn('program_id', $programsIdsArray)->orderBy('published_at', 'desc')->paginate(60);
            }
        }
        
        return $episodes;

    }





}
