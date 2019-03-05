<?php

namespace App\Http\Controllers;

use App\Category;
use App\EpisodeSocialAutopost;
use App\ProgramMeta;
use App\EpisodeMeta;
use App\SocialAutopost;
use App\Subscription;
use App\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Program;
use App\Tag;
use App\Episode;
use SimplePie;
use Auth;
use Log;
use Cache;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programs = Program::all();
        return view('programs.index', ['programs' => $programs]);
    }


    public function subscribe(Request $request) {
        $program_id = $request->input('program_id');

        Log::debug("program_id: {$program_id}");

        if (Auth::check()) {
            try {

                $subscription = Subscription::Create([
                    'program_id' => $program_id,
                    'user_id' => Auth::user()->id,
                ]);

                Log::debug("Subscribed OK! {$subscription}");

                $key = Str::slug( "program-" . $subscription->program->slug );
                Log::debug("subscribe cache flush key: ". $key);
                Cache::forget($key);

            } catch (Exception $e) {
                //throw new Exception($e);
                Log::error($e);
            }
        }
    }

    public function unsubscribe(Request $request) {
        $program_id = $request->input('program_id');

        Log::debug("program_id: {$program_id}");

        if (Auth::check()) {
            try {

                $subscription = Subscription::with('program')->where('program_id', $program_id)->first();
                $deletedRows = Subscription::where('program_id', $program_id)->where('user_id', Auth::user()->id)->delete();

                Log::debug("Unsubscribed OK! {$deletedRows}");

                $key = Str::slug( "program-" . $subscription->program->slug );
                Log::debug("unsubscribe cache flush key: ". $key);
                Cache::forget($key);

            } catch (Exception $e) {
                //throw new Exception($e);
                Log::error($e);
            }
        }
    }




    public function subscription_manager()
    {
        $user = User::find(Auth::user()->id);

        $subscriptions = $user->subscriptions()->with('program.logo')->get();

        //dd($subscriptions);

        return view('subscriptions.manager', ['subscriptions' => $subscriptions]);
    }


    public function get_subscription_data($program){

        /*
         * SUBSCRIPTION LOGIC
         */
        $subscription = new Subscription;
        $subscription->subscribed = false;
        $subscription->count = (isset($program->subscriptions) ? count($program->subscriptions) : 0 );


        if(Auth::check()) {
            if(count($program->subscriptions->where('user_id', '=', Auth::user()->id)->all()) > 0) {
                $subscription->subscribed = true;
            }
        }

        //Log::debug("Program Subscription ".print_r($subscription, true));

        //subscriptionData
        return [
            "program_id" => $program->id,
            "subscribed" => $subscription->subscribed,
            "subscription_count" => $subscription->count
        ];


    }


    public function program_by_slug($slug){

        return $this->get(Program::with(['episodes' => function($query) { $query->orderBy('published_at', 'desc')->get(); }])
            ->with('logo')
            ->with('twitters')
            ->with('facebooks')
            ->with('googleplus')
            ->with('emails')
            ->with('subscriptions')
            ->where('slug',$slug)->first());
        
    }

    public function get($program){

        if($program==null) return new Program;
        $program->subscribed_count = count($program->subscriptions);
        $program->subscribed = false;
        if(Auth::check()) {
            if(count($program->subscriptions->where('user_id', '=', Auth::user()->id)->all()) > 0) {
                $program->subscribed = true;
            }
        }

        return $program;
    }

}
