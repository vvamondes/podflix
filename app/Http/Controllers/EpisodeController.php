<?php

namespace App\Http\Controllers;

use App\Program;
use Illuminate\Http\Request;

use Requests;
use App\Episode;
use App\Like;
use App\Player;
use Auth;
use Log;
use Session;
use Carbon\Carbon;
use DB;
use App\Catalog;
use Cache;
use Illuminate\Support\Str;

class EpisodeController extends Controller
{

    protected $programController;
    protected $playlistController;
    public function __construct(ProgramController $programController, PlaylistController $playlistController)
    {
        $this->programController = $programController;
        $this->playlistController = $playlistController;
    }



    public function like(Request $request)
    {

        $episode_id = $request->input('episode_id');
        $liked = boolval($request->input('liked'));

        //Log::debug("like episode_id: {$episode_id} liked: {$liked}");

        $like = $this->register_like_dislike($request, $episode_id, $liked, true);

        //Log::debug("LIKED OK! {$like}");

    }

    public function dislike(Request $request)
    {

        $episode_id = $request->input('episode_id');
        $liked = boolval($request->input('liked'));

        //Log::debug("like episode_id: {$episode_id} liked: {$liked}");

        $like = $this->register_like_dislike($request, $episode_id, $liked, false);

        //Log::debug("LIKED OK! {$like}");

    }


    public function register_like_dislike($request, $episode_id, $liked, $action){

        if (Auth::check()) {
            try {

                $like = Like::with('episode')->where('episode_id', $episode_id)->where('user_id', Auth::user()->id)->first();
                
                //Log::debug(print_r($like, true));

                //0 caso nao exista um like ou dislike
                if($like === null){

                    //Log::debug("0 caso nao exista um like ou dislike");

                    //create like row
                    $like = Like::Create(
                        [
                            'episode_id' => $episode_id,
                            'liked' => $liked,
                            'user_id' => Auth::user()->id,
                        ]
                    );
                    
                    if($liked){
                        //Log::debug("caso usuario curta");
                        $like->episode->liked_count += 1;
                    }
                    if(!$liked){
                        //Log::debug("caso usuario descurta");
                        $like->episode->disliked_count += 1;
                    }
                    //save episode count
                    $like->episode->save();
                    

                //1 caso ja exista um like ou dislike
                }else{

                    //Log::debug("1 caso ja exista um like ou dislike");

                    //click no botao de dislike
                    if (!$action){
                        
                        if($like->liked == false){
                            $like->episode->disliked_count -= 1;
                            $deletedRows = Like::where('episode_id', $episode_id)->where('user_id', Auth::user()->id)->delete();
                            //Log::debug("caso usuario descurta o descurtido");
                        }
                    
                        if($like->liked == true){
                            $like->episode->disliked_count += 1;
                            $like->episode->liked_count -= 1;
                            //Log::debug("caso usuario descurta o curtido");
                        }

                    }

                    //click no botao de like
                    if ($action){
                        
                        if($like->liked == false){
                            $like->episode->liked_count += 1;
                            $like->episode->disliked_count -= 1;
                            //Log::debug("caso usuario curta o descurtido");
                        }
                    
                        if($like->liked == true){
                            $like->episode->liked_count -= 1;
                            //Log::debug("caso usuario curta o curtido");
                            $deletedRows = Like::where('episode_id', $episode_id)->where('user_id', Auth::user()->id)->delete();
                        }

                    }
                    
                    
                    //save likes
                    $like->liked = $liked;
                    $like->save();
                    //save episode count
                    $like->episode->save();

                } 


                $key = Str::slug( "episode-" . $like->episode->slug );
                //Log::debug("episode cache flush key: ". $key);
                Cache::forget($key);

            } catch (Exception $e) {
                throw new Exception($e);
            }

        }else{
            Log::debug("like episode_id: {$episode_id} not registered. User not logged.");
        }

    }


    public function register_player_event($request, $episode_id, $event_id){

        try{

            Log::debug("register_player_event episode_id: {$episode_id} event_id: {$event_id} ip: {$request->ip()}");

            $player = Player::Create([
                'episode_id' => $episode_id,
                'user_id' => (Auth::check()) ? Auth::user()->id :  null,
                'event_id' => $event_id,
                'ip' => $request->ip(),
            ]);

            $episode = $player->episode()->first();

            if($event_id==1) $episode->played_count += 0.6; //start play
            if($event_id==2) $episode->played_count += 0.4; //finished

            $episode->save();

            //Log::debug("register_player_event episode ".print_r($episode, true));
            //Log::debug("register_player_event episode event");

        } catch (Exception $e) {
            //throw new Exception($e);
            Log::error($e);
        }

    }


    public function play(Request $request) {
        $episode_id = $request->input('episode_id');

        Log::debug("play episode_id: {$episode_id}");

        $player = $this->register_player_event($request, $episode_id, 1);

        Log::debug("PLAYED OK! {$player}");

    }



    public function finish(Request $request) {
        $episode_id = $request->input('episode_id');

        Log::debug("finish episode_id: {$episode_id}");

        $player = $this->register_player_event($request, $episode_id, 2);

        Log::debug("FINISHED OK! {$player}");

    }



    

    public function episode_by_slug($slug_episode){

        return $this->get(Episode::with('program.logo')->where('slug',$slug_episode)->first());

    }


    public function get($episode)
    {
        
        if($episode==null) return new Episode;

        /*
         * LIKES LOGIC
         */

        if(Auth::check()) {
            $like = Like::where('episode_id', '=', $episode->id)->where('user_id', '=', Auth::user()->id)->first();

            //dd($like);

            if(isset($like) && $like != null){
                $episode->liked = (bool) $like->liked;
            }
        }

        return $episode;

    }


    public function catalog_by_slug($slug_catalog) {

        return Catalog::where('slug',$slug_catalog)->first();

    }


    public function episodes_catalog($catalog) {

        $categories = $catalog->categories()->get();

        $episodes = $this->episodes_categories($categories);

        return $episodes;

    }


    public function episodes_categories($categories, $limit = 30) {

        $episodes = new Episode;

        $programsIdsArray = array();
        foreach ($categories as $category) {
            foreach ($category->programs as $program) {
                $programsIdsArray[] = $program->id;
            }
        }

        if(count($programsIdsArray)>0) {
            $episodes = Episode::with('program.logo')->whereIn('program_id', $programsIdsArray)->orderBy('published_at','desc')->paginate($limit);
        }

        return $episodes;

    }



    public function episodes_trending($limit = 30) {

        $yesterday = Carbon::yesterday();
        //dd($yesterday);
        
        $episodes_raw = DB::table('players')->selectRaw('episode_id, count(episode_id)')
        ->where('created_at', '>=', $yesterday)
        ->groupBy('episode_id')->orderBy('COUNT(episode_id)','desc')->paginate($limit);

        //dd($episodes_raw);

        $idsArray = array();
        foreach ($episodes_raw as $episode) {
            $idsArray[] = $episode->episode_id;
        }

        $episodes = Episode::with('program.logo')->whereIn('id', $idsArray)->orderBy('published_at','DESC')->paginate($limit);

        return $episodes;


    }

    public function episodes_featured($limit = 30) {

        //TIME_TO_SEC > 15:00
        $episodes = Episode::with('program.logo')->whereRaw('TIME_TO_SEC( LPAD(duration, 8, "00:00:00") ) >= 900')->orderBy('published_at','DESC')->paginate($limit);

        return $episodes;

    }

    public function episodes_newbies($limit = 30) {

        $programs = Program::whereBetween('episodes_count', [1, 10])->get();

        $programsIdsArray = array();
        foreach ($programs as $program) {
            $programsIdsArray[] = $program->id;
        }

        //TIME_TO_SEC > 15:00
        $episodes = Episode::with('program.logo')->whereIn('program_id', $programsIdsArray)->whereRaw('TIME_TO_SEC( LPAD(duration, 8, "00:00:00") ) >= 900')->orderBy('published_at','DESC')->paginate($limit);

        return $episodes;

    }

    public function episodes_drops($limit = 30) {

        $episodes = Episode::with('program.logo')->whereRaw('TIME_TO_SEC( LPAD(duration, 8, "00:00:00") ) < 900')->orderBy('published_at','DESC')->paginate($limit);

        return $episodes;

    }


}
