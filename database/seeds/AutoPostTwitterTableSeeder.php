<?php

use Illuminate\Database\Seeder;
use App\Program;
use App\Episodes;
use App\EpisodeAutopost;
use App\EpisodeTwitter;
use Carbon\Carbon;
use Edujugon\SocialAutoPost\SocialAutoPost as SocialAutoPost;
use Mbarwick83\Shorty\Facades\Shorty as Shorty;

class AutoPostTwitterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {
        $yesterday = Carbon::yesterday();
        //dd($yesterday);
        
        $autopost_episodes = EpisodeAutopost::whereNull('published_at')->where('created_at', '>=', $yesterday)->where('social', '=', 'twitter')->orderBy('created_at','asc')->take(4)->get();
        /*
            SELECT * FROM episode_autoposts left join episodes on episode_id = episodes.id 
            WHERE
            episode_autoposts.social = 'twitter' 
            and episode_autoposts.published_at is null 
            and episode_autoposts.created_at >= '2017-02-18 00:00:00'
            and episodes.published_at >= '2017-02-18 00:00:00'
            order by episode_autoposts.created_at asc
        */
            /*
        $autopost_episodes = DB::table('episode_autoposts')
            ->leftJoin('episodes', 'episode_autoposts.episode_id', '=', 'episodes.id')
            ->whereNull('episode_autoposts.published_at')->where('episode_autoposts.created_at', '>=', $yesterday)->where('episode_autoposts.social', '=', 'twitter')
            ->where('episodes.published_at', '>=', $yesterday)
            ->orderBy('episode_autoposts.created_at','asc')
            ->take(2)
            ->get();
            */

        //DB::connection()->enableQueryLog();
        
        $autopost_episodes = EpisodeAutopost::
            join('episodes', 'episode_autoposts.episode_id', '=', 'episodes.id')
            ->whereNull('episode_autoposts.published_at')->where('episode_autoposts.created_at', '>=', $yesterday)->where('episode_autoposts.social', '=', 'twitter')
            ->where('episodes.published_at', '>=', $yesterday)
            ->orderBy('episode_autoposts.created_at','asc')
            ->take(2)
            ->get([
            'episode_autoposts.*'
        ]);
        
        //$autopost_episodes = $autopost_episodes;
        
        //$last_query = end($queries);

        

        foreach ($autopost_episodes as $index=>$autopost) {
        	
        	//print_r($autopost->episode->twitters);

            try {

                //$twitter = print_r($autopost->episode->twitters, true);
                Log::debug("AutoPostTwitter " . $autopost->episode->title . " INICIO");

                $autopost->published_at = Carbon::now();
                $autopost->save();
                
                $url = config('app.url') . "/" . $autopost->episode->program->slug . "/" . $autopost->episode->slug;
                $link = Shorty::shorten($url);

                $tweet = $autopost->episode->title . " " . $link;

                //mentions
                //Your app has been restricted or suspended due to one or more violations
                /*
                foreach ($autopost->episode->twitters as $index=>$user) {
                    
                    Log::debug("AutoPostTwitter @" . $user->name);

                    if($index == 0){
                        $message=$tweet." com @".$user->name;
                    } else {
                        $message=$tweet." @".$user->name;    
                    }
                    
                    if(count($message) <= 140) $tweet = $message;

                    Log::debug("AutoPostTwitter composing " . $tweet);

                }
                */


                //hashtags
                $hashtags = array(
                        array('name' => 'Podcast', 'dayofweek' => 'EVERYDAY'),
                        array('name' => 'PodcastFriday', 'dayofweek' => Carbon::FRIDAY) 
                );

                foreach ($hashtags as $index => $tag) {
                    $tag = (object) $tag;

                    ///Log::debug("AutoPostTwitter #" . $tag->name);

                    if(Carbon::now()->dayOfWeek == $tag->dayofweek || $tag->dayofweek == 'EVERYDAY'){
                        $message=$tweet." #".$tag->name;
                    }

                    if(count($message) <= 140) $tweet = $message;
                }


                Log::debug("AutoPostTwitter [" . $tweet . "] TWEET");

                $social = new SocialAutoPost('twitter');
                $social->params(['status' => $tweet])->post()->withFeedback();
                $feedback = print_r($social->getFeedback(), true);

                //Log::debug("AutoPostTwitter getFeedback " . $feedback);

                Log::debug("AutoPostTwitter " . $autopost->episode->title . " FIM");
            } catch (Exception $e) {
                // All other exceptions
                Log::debug("AutoPostTwitter " . $autopost->episode->title . " " . $e);
            }
	        
            //$queries = DB::getQueryLog();
            //Log::debug("AutoPostTwitter query " . print_r($queries, true));
        }
    }
}
