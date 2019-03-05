<?php

namespace App\Http\Controllers;

use App\Category;
use App\EpisodeAutopost;
use App\EpisodeTwitter;
use App\SocialAutopost;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Program;
use App\Tag;
use App\User;
use App\Episode;
use App\ProgramImage;
use App\ProgramTwitter;
use App\ProgramFacebook;
use App\ProgramGoogleplus;
use App\ProgramEmail;
use App\ProgramSite;
use App\UserProgramRequest;
use App\Mail\WelcomeNewProgram;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;
use League\Flysystem\Exception;
use SimplePie;

use Storage;

use Mail;
use Cache;
use Illuminate\Support\Str;


class FeedController extends Controller
{

    function getFeed($url){
        $feed = new SimplePie();

        $feed->set_feed_url($url);

        $feed->enable_cache(false);
        $feed->set_timeout(30);
        $feed->set_item_limit(0);

        //$feed->set_stupidly_fast(true);
        //$feed->enable_order_by_date(true);

        //$feed->set_output_encoding('Windows-1252');

        //$feed->set_useragent('Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0'); //IMPORTANT!
        //$feed->set_useragent('Mozilla/5.0 (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Mobile/7B405'); //IMPORTANT!
        //$feed->set_useragent('Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36'); //IMPORTANT!
        $feed->set_useragent('Podflix'); //IMPORTANT!

        $feed->init();

        $feed->handle_content_type();

        //dd($feed);

        if ($feed->error()) {
            //dd($feed->error());
            throw new Exception($feed->error());
            //return;
        }

        return $feed;
    }

    public function updateProgramInfosFromProgramId(Request $request, $id)
    {
        $program = Program::find($id);

        Log::debug("updateProgramInfosFromProgramId Program: $program->name Feed: $program->feed");

        $this->updateProgramInfosFromFeed($program, true);

    }

    public function updateProgramInfosFromFeed($program, $update_images = false)
    {
        try {
            $feed = $this->getFeed($program->feed);
        }catch (Exception $e){
            throw new Exception($e);
        }

        $program->name = ($feed->get_title()!="") ? $feed->get_title() : $program->name;
        $program->description = $feed->get_description();
        $program->slug = SlugService::createSlug(Program::class, 'slug', $program->name);
        $program->activated = true;
        $program->save();

        if($update_images) $this->saveProgramImages($feed, $program);
        $this->saveProgramCategories($feed, $program);

    }


    public function updateProgramCheckedAt($program)
    {
        $program->checked_at = Carbon::now();
        $program->save();
    }


    public function createProgramsAndEpisodesFromFeed($url, $limit = null, $id = null)
    {
        try {
            $feed = $this->getFeed($url);
        }catch (Exception $e){
            throw new Exception($e);
        }

        $program = new Program;

        if($id!=null) $program->id = $id;

        $program->name = $feed->get_title();
        $program->description = $feed->get_description();
        $program->slug = SlugService::createSlug(Program::class, 'slug', $program->name);
        $program->feed = $url;
        $program->activated = true;

        $this->saveNewProgram($feed, $program);

        $this->saveEpisodes($feed, Program::where('name',$program->name)->first(), $limit);

    }

    public function loadProgramAndEpisodesFromFeed(Request $request)
    {
        $feed = $request->input('feed');
        $limit = $request->input('limit');
        $this->createProgramsAndEpisodesFromFeed($feed, $limit);

    }

    public function loadEpisodes(Request $request, $id)
    {
        $program = Program::find($id);

        $limit = $request->input('limit');

        Log::debug("loadEpisodes Program: $program->name Feed: $program->feed");

        $this->loadEpisodesFromProgram($program, $limit);

    }

    public function loadEpisodesFromProgram($program, $limit = 3)
    {
        try {
            $feed = $this->getFeed($program->feed);
        }catch (Exception $e){
            throw new Exception($e);
        }

        $this->saveEpisodes($feed, $program, $limit);
    }


    public function saveEpisodeTags($item, $episode)
    {
        if ($enclosure = $item->get_enclosure())
        {
            foreach ((array) $enclosure->get_keywords() as $keyword)
            {
                $tag = new Tag;
                $tag->name = $keyword;
                $tag->slug = SlugService::createSlug(Tag::class, 'slug', $tag->name);

                if ($tag->validate($tag->toArray())) {
                    $tag->save();
                }else{
                    $tag  = Tag::where('name', $tag->name)->get()->first();
                }

                //if(!$episode->tags->contains($tag->id)) {
                if(count($episode->tags) > 0 && !$episode->tags->contains($tag->id)) {
                    $episode->tags()->attach($tag);
                    $tag->update(['count' => $tag->episodes->count()]);
                }
            }
        }


    }



    public function saveEpisodeTwitters($item, $episode)
    {

        //Procura @Twitters
        $stringTwitters = $item->get_content();
        $twitters_episode = ProgramTwitter::where('program_id', $episode->program->id)->get();

        //Adiciona os twitters fixos do programa para entrar no episodio
        foreach ($twitters_episode as $t) {
            EpisodeTwitter::create([
                'episode_id' => $episode->id,
                'name' => $t->name,
            ]);
        }

        $stringTwitters = preg_replace('/twitter.com\/[share|search|home]+[\/|\?|\"|\']/i', ' ', $stringTwitters); //remove links de busca, hash ou share
        $stringTwitters = preg_replace('/[,|.|;]/i', ' ', $stringTwitters); //remove " . , ; "
        $stringTwitters = preg_replace('/twitter.com\//i', ' @', $stringTwitters); //trata os usuarios com links
        $stringTwitters = preg_replace('/\(|\)/i', '', $stringTwitters); //trata os usuarios com textos
        $twitter_c      = preg_match_all('/(^|\s)@(\w+)/', $stringTwitters, $matches); //encontra os usuarios

        //Adiciona os twitters achados no feed para entrar no episodio
        for ($i = 0; $i < $twitter_c; $i++) {
            $twitter_value = trim($matches[0][$i]);
            $twitter_value = str_replace("@","",$twitter_value);
            $twitter_value = clean($twitter_value, "twitter");

            if (EpisodeTwitter::where('episode_id',$episode->id)->where('name',$twitter_value)->get()->first() == null) {
                EpisodeTwitter::create([
                    'episode_id' => $episode->id,
                    'name' => $twitter_value,
                ]);
            }
        }

    }

    public function saveEpisodeAutopost($episode)
    {

        EpisodeAutopost::create([
            'program_id' => $episode->program->id,
            'episode_id' => $episode->id,
            'social' => 'twitter',
        ]);

        EpisodeAutopost::create([
            'program_id' => $episode->program->id,
            'episode_id' => $episode->id,
            'social' => 'facebook',
        ]);

    }

    public function updateEpisodesCountFromProgram($program)
    {
        $program->episodes_count = $program->episodes()->count();
        $program->save();
    }


    public function saveProgramCategories($feed, $program)
    {

        //ADICIONA CATEGORIAS AOS PROGRAMAS, CASO NAO EXISTA ELA SERA CRIADA!
        $iTunesCategories=$feed->get_channel_tags(SIMPLEPIE_NAMESPACE_ITUNES,'category');
        if ($iTunesCategories) {
            //dd($iTunesCategories);
            foreach ($iTunesCategories as $iTunesCategory) {
                try{
                    $category_name = $iTunesCategory['attribs']['']['text'];

                    $category = new Category;
                    $category->name = $category_name;
                    $category->slug = SlugService::createSlug(Category::class, 'slug', $category_name);

                    if ($category->validate($category->toArray())) {
                        $category->save();
                    }else{
                        $category  = Category::where('name', $category_name)->get()->first();
                    }
                    //dd($category);

                    if(!$program->categories->contains($category->id)) {
                        $program->categories()->attach($category);
                        $category->update(['count' => $category->programs->count()]);
                    }

                } catch (Exception $e) {
                    // All other exceptions
                    Log::debug("saveProgramCategories: ". $e);
                }
            }
        }

    }


    public function saveProgramImages($feed, $program)
    {

        $path = env('S3_PATH_IMAGES_PROGRAMS');
        $id = uniqid();
        $filename_small = $id."-small.jpg";
        $filename_medium = $id."-medium.jpg";
        $filename_large = $id."-large.jpg";

        //Save DB
        $image = ProgramImage::where('program_id',$program->id);
        $program_image = ($image->exists()) ? $image->first() : new ProgramImage;
        $program_image->program_id = $program->id;
        $program_image->small = $filename_small;
        $program_image->medium = $filename_medium;
        $program_image->large = $filename_large;

        Log::debug("saveProgramImages save() ". $program->name);
        $program_image->save();

        if($feed->get_image_url()==null){

            $program_image->small = null;
            $program_image->medium = null;
            $program_image->large = null;
            Log::debug("saveProgramImages save() images[small, medium, large] == NULL");
            $program_image->save();
        
        }else{

            try{
                //UPLOAD!
                $image_small = Image::make($feed->get_image_url())->resize(350, 350)->encode('jpg', 75);
                $image_medium = Image::make($feed->get_image_url())->resize(700, 700)->encode('jpg', 75);
                $image_large = Image::make($feed->get_image_url())->resize(1400, 1400)->encode('jpg', 75);
                
                //Upload to S3
                $headers = ['visibility' => 'public', 'ContentType' => 'image/jpeg', 'CacheControl' => 'max-age=630720000, public', 'Expires' => 'Wed, 18 May 2033 03:33:20 GMT'];
                Log::debug("saveProgramImages Storage::cloud() uploading... " . $filename_small);
                Storage::cloud()->getDriver()->put($path . $filename_small, $image_small->__toString(), $headers);
                Log::debug("saveProgramImages Storage::cloud() uploading... " . $filename_medium);
                Storage::cloud()->getDriver()->put($path . $filename_medium, $image_medium->__toString(), $headers);
                Log::debug("saveProgramImages Storage::cloud() uploading... " . $filename_large);
                Storage::cloud()->getDriver()->put($path . $filename_large, $image_large->__toString(), $headers);
            } catch (Exception $e) {
                Log::debug("saveProgramImages UPLOAD: ". $e);
            }
        }


        //dd($programmeta);
    }



    function saveNewProgram($feed, $program){

        Log::debug("saveNewProgram ". $program->name);

        try {

            if ($program->validate($program->toArray())) {
                $program->save();
                Log::debug("saveNewProgram save() ". $program->name);

                $this->saveProgramImages($feed, $program);
                $this->saveProgramCategories($feed, $program);
                return $program;
            }

        } catch (Exception $e) {
            // All other exceptions
            Log::debug("saveNewProgram " . $item->get_title() . ": ". $e);
        }

        return null;        

    }


    function saveEpisodes($feed, $program, $limit = null){

        if($limit == null) { $limit = 3; }
        Log::debug("saveEpisodes Starting ". $limit . " max episodes to process");

        foreach ($feed->get_items(0, $limit) as $index => $item){

            Log::debug("saveEpisodes Episode " . ($index) . "/" . $limit . " " . $item->get_title());

            $episode = new Episode;
            $episode->program_id = $program->id;

            $title = $item->get_title();
            $title = htmlspecialchars_decode($title);
            $title = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u',' ', $title);
            $title = htmlspecialchars($title, ENT_NOQUOTES, "UTF-8");

            $content = $item->get_content();
            $content = htmlspecialchars_decode($content);
            $content = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u',' ', $content);
            $content = htmlspecialchars($content, ENT_NOQUOTES, "UTF-8");

            $episode->title = clean($title, 'title');
            $episode->published_at = $item->get_date('Y-m-d H:i:s');
            $episode->content = clean($content, 'content');
            $episode->slug = SlugService::createSlug(Episode::class, 'slug', $item->get_title());

            foreach ($item->get_enclosures() as $enclosure)
            {
                $episode->file_url = $enclosure->get_link();
                $episode->file_length = $enclosure->get_length();
                $episode->file_type = $enclosure->get_type();
                $episode->duration = $enclosure->get_duration(true);

                try{if($episode->validate($episode->toArray())) break;} catch (Exception $e) {}
            }
            
            //Log::debug("saveProgram before validate() and save() ". print_r($episode, true));

            try {

                if ($episode->validate($episode->toArray())) {
                    Log::debug("saveEpisodes validate() TRUE! ". $episode->title);
                    $episode->save();
                    Log::debug("saveEpisodes save() OK! ". $episode->title);
                    $this->saveEpisodeTags($item, $episode);
                    $this->saveEpisodeTwitters($item, $episode);
                    $this->saveEpisodeAutopost($episode);
                    $this->updateEpisodesCountFromProgram($program);
                    $this->forgetCaches($episode);
                }else{
                    throw new Exception("saveEpisodes validate() FALSE! " . $item->get_title() . " is NOT a podcast episode");
                }

            } catch (Exception $e) {
                // All other exceptions
                //Log::debug("saveEpisodes " . $item->get_title() . ": ". $e);
            }

        }

        Log::debug("saveEpisodes Ended ". $limit . " episodes processed");

    }


    protected function keygen( $url )
    {
        return Str::slug( $url );
    }

    function forgetCaches($program){

                //forget program with episodes list
                $key = Str::slug( "program-" . $program->slug); Cache::forget($key);

                //forget home
                $key = $this->keygen("trending-home"); Cache::forget($key);
                $key = $this->keygen("featured-home"); Cache::forget($key);
                $key = $this->keygen("newbies-home"); Cache::forget($key);
                $key = $this->keygen("drops-home"); Cache::forget($key);

                //forget trending, featured, newbies
                for ($i = 0; $i < 10; $i++) {

                    $key = $this->keygen("trending".$i); Cache::forget($key);
                    $key = $this->keygen("featured".$i); Cache::forget($key);
                    $key = $this->keygen("newbies".$i); Cache::forget($key);
                    $key = $this->keygen("drops".$i); Cache::forget($key);

                 }
    }



    function feedRequestProgramCreateCheck(Request $request){

        $url = $request->input('feed');
         try {
            $feed = $this->getFeed($url);
        }catch (Exception $e){
            throw new Exception($e);
        }

        $userProgramRequest = new UserProgramRequest;

        $userProgramRequest->name = $feed->get_title();
        $userProgramRequest->description = $feed->get_description();
        $userProgramRequest->feed = $url;
        $userProgramRequest->logo = $feed->get_image_url();

       
        //EMAIL 
        $pattern = "/[a-z0-9!#$%&'*+?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/i";

        $string = $feed->raw_data; //remove dominios externos
        $string = str_replace("@podcastgarden.com","",$string);
        $string = str_replace("@soundcloud.com","",$string);
        preg_match_all($pattern, $string, $emails);

        if(count($emails)>0){
            $userProgramRequest->email = implode(", ", array_unique($emails[0]));
        }


        foreach ($feed->get_links() as $link)
        {
                $result = parse_url($link);
                $userProgramRequest->site = $result['scheme']."://".$result['host'];
                break;
        }


        

        return $userProgramRequest;
    }



    public function createProgramsAndEpisodesFromUserProgramRequestId(UserProgramRequest $userProgramRequest, $limit = 3)
    {

        $userProgramRequest->status = "in progress";
        $userProgramRequest->save();

        try {
            $feed = $this->getFeed($userProgramRequest->feed);
        }catch (Exception $e){
            throw new Exception($e);
        }

        $program = new Program;

        $program->name = $userProgramRequest->name;
        $program->description = $userProgramRequest->description;
        $program->slug = SlugService::createSlug(Program::class, 'slug', $program->name);
        $program->feed = $userProgramRequest->feed;
        $program->activated = true;

        try{
            $program = $this->saveNewProgram($feed, $program);
            
            if($program!=null){

                $this->saveNewProgramSocials($program, $userProgramRequest);
                $userProgramRequest->status = "done";
                $userProgramRequest->program_id = $program->id;
                $userProgramRequest->save();

                $this->saveEpisodes($feed, Program::where('name', $program->name)->first(), $limit);
                
                $user = User::find($userProgramRequest->user_id);        
                if($user!=null && $user->email!=null) {
                    $userProgramRequest->replied_at = Carbon::now();
                    $userProgramRequest->save();
                    $email = new WelcomeNewProgram($program);
                    Mail::to($user->email)->send($email);
                    Log::debug("createProgramsAndEpisodesFromUserProgramRequestId email sent to $user->email");
                }

            }
        }catch (Exception $e){
            throw new Exception($e);
        }

    }


    function saveNewProgramSocials($program = null, $userProgramRequest){

        Log::debug("saveNewProgramSocials ". $userProgramRequest->name);

        if($program==null) return null;

        try {

            if($userProgramRequest->twitter!=null){
                $social = new ProgramTwitter;
                $social->program_id = $program->id;
                $social->name = $userProgramRequest->twitter;
                $social->save();
                Log::debug("saveNewProgramSocials Twitter saved");
            }

            if($userProgramRequest->facebook!=null){
                $social = new ProgramFacebook;
                $social->program_id = $program->id;
                $social->name = $userProgramRequest->facebook;
                $social->save();
                Log::debug("saveNewProgramSocials Facebook saved");
            }

            if($userProgramRequest->googleplus!=null){
                $social = new ProgramGoogleplus;
                $social->program_id = $program->id;
                $social->name = $userProgramRequest->googleplus;
                $social->save();
                Log::debug("saveNewProgramSocials GooglePlus saved");
            }

            if($userProgramRequest->email!=null){
                $social = new ProgramEmail;
                $social->program_id = $program->id;
                $social->name = $userProgramRequest->email;
                $social->save();
                Log::debug("saveNewProgramSocials Email saved");
            }
            
            if($userProgramRequest->site!=null){
                $social = new ProgramSite;
                $social->program_id = $program->id;
                $social->name = $userProgramRequest->site;
                $social->save();
                Log::debug("saveNewProgramSocials Site saved");
            }

        } catch (Exception $e) {
            // All other exceptions
            Log::debug("saveNewProgramSocials " . $userProgramRequest->name . ": ". $e);
        }

        

    }





}