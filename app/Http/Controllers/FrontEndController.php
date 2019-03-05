<?php

namespace App\Http\Controllers;

use Log;
use Cache;
use SEOMeta;
//use Artesaos\SEOTools\Facades\TwitterCard as TwitterCard;
use OpenGraph;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class FrontEndController extends Controller
{
    protected $episodeController;
    protected $programController;
    protected $playlistController;
    protected $subscriptionController;
    protected $searchController;
    protected $userProgramRequestController;

    public function __construct(
    	EpisodeController $episodeController, 
    	ProgramController $programController,
    	PlaylistController $playlistController,
    	SubscriptionController $subscriptionController,
    	SearchController $searchController,
        UserProgramRequestController $userProgramRequestController
    	)
    {
        $this->episodeController = $episodeController;
        $this->programController = $programController;
        $this->playlistController = $playlistController;
        $this->subscriptionController = $subscriptionController;
        $this->searchController = $searchController;
        $this->userProgramRequestController = $userProgramRequestController;
    }

    protected function keygen( $url )
    {
        return Str::slug( $url );
    }

    public function home(Request $request)
    {

        $this->setSEO("Um espaço que reúne os melhores podcasts brasileiros", true);

        $expiresAt = Carbon::now()->addMinutes(15);

        $key = $this->keygen("featured-home");
        if( ! Cache::has($key)) Cache::put($key, $this->episodeController->episodes_featured(12), $expiresAt);
        $episodes = Cache::get($key);
        $sections[] = (object)['title' => "Lançamentos", 'link' => "/featured", 'episodes' => $episodes, 'count' => count($episodes)];

        $key = $this->keygen("trending-home");
        if( ! Cache::has($key)) Cache::put($key, $this->episodeController->episodes_trending(6), $expiresAt);
        $episodes = Cache::get($key);
        $sections[] = (object)['title' => "Em Alta", 'link' => "/trending", 'episodes' => $episodes, 'count' => count($episodes)];

        $key = $this->keygen("newbies-home");
        if( ! Cache::has($key)) Cache::put($key, $this->episodeController->episodes_newbies(6), $expiresAt);
        $episodes = Cache::get($key);
        $sections[] = (object)['title' => "Novos Programas", 'link' => "/newbies", 'episodes' => $episodes, 'count' => count($episodes)];

        $key = $this->keygen("drops-home");
        if( ! Cache::has($key)) Cache::put($key, $this->episodeController->episodes_drops(6), $expiresAt);
        $episodes = Cache::get($key);
        $sections[] = (object)['title' => "Drops", 'link' => "/drops", 'episodes' => $episodes, 'count' => count($episodes)];

        return view('welcome')->with('sections',$sections);

    }


    public function subscription_manager(){

    	$this->setSEO("Gerenciador de Inscrições");

    	return $this->programController->subscription_manager();

    }


    public function playlist(){

    	$this->setSEO("Playlist");

		return view('playlist.show', ['playlist' => $this->playlistController->get()]);

    }


    public function subscriptions(){

    	$this->setSEO("Inscrições");

        return view('subscriptions.show', ['episodes' => $this->subscriptionController->get()]);

    }


    public function trending(Request $request){

    	$description = "Em Alta"; 
    	$this->setSEO($description);

        $expiresAt = Carbon::now()->addMinutes(15);

        $key = $this->keygen("trending" . implode($request->all()));
        if( ! Cache::has($key)) Cache::put($key, $this->episodeController->episodes_trending(), $expiresAt);
        $episodes = Cache::get($key);

        //Log::debug("CACHE key " . $key);

        return view('episodes.list', [
        	'title' => $description, 
        	'episodes' => $episodes
        	]);

    }

	public function featured(Request $request){

		$description = "Lançamentos"; 
    	$this->setSEO($description);

        $expiresAt = Carbon::now()->addMinutes(15);

        $key = $this->keygen("featured" . implode($request->all()));
        if( ! Cache::has($key)) Cache::put($key, $this->episodeController->episodes_featured(), $expiresAt);
        $episodes = Cache::get($key);

        //Log::debug("CACHE key " . $key);

        return view('episodes.list', [
        	'title' => $description,
        	'episodes' => $episodes
        	]);

    }

    public function newbies(Request $request){

		$description = "Novos Programas"; 
    	$this->setSEO($description);

        $expiresAt = Carbon::now()->addMinutes(15);
    	
        $key = $this->keygen("newbies" . implode($request->all()));
        if( ! Cache::has($key)) Cache::put($key, $this->episodeController->episodes_newbies(), $expiresAt);
        $episodes = Cache::get($key);

        //Log::debug("CACHE key " . $key);

        return view('episodes.list', [
            'title' => $description,
            'episodes' => $episodes
            ]);

    }

    public function drops(Request $request){

        $description = "Drops"; 
        $this->setSEO($description);

        $expiresAt = Carbon::now()->addMinutes(15);
        
        $key = $this->keygen("drops" . implode($request->all()));
        if( ! Cache::has($key)) Cache::put($key, $this->episodeController->episodes_drops(), $expiresAt);
        $episodes = Cache::get($key);

        //Log::debug("CACHE key " . $key);

        return view('episodes.list', [
            'title' => $description,
            'episodes' => $episodes
            ]);

    }

	public function search(Request $request){

		$this->setSEO($request->input('q'));

        $expiresAt = Carbon::now()->addMinutes(15);

        $key = $this->keygen($request->url() . "-episodes-" . implode($request->all()));
        if( ! Cache::has($key)) Cache::put($key, $this->searchController->episodes($request), $expiresAt);
        $episodes = Cache::get($key);

        $key = $this->keygen($request->url() . "-programs-" . implode($request->all()));
        if( ! Cache::has($key)) Cache::put($key, $this->searchController->programs($request), $expiresAt);
        $programs = Cache::get($key);

		return view('search.index', [
			'programs' => $programs,
		 	'episodes' => $episodes
		 	]);

    }

    public function catalog(Request $request, $slug){

        $expiresAt = Carbon::now()->addMinutes(15);

        $key = $this->keygen($request->url() . "-catalog-" . implode($request->all()));
        if( ! Cache::has($key)) Cache::put($key, $this->episodeController->catalog_by_slug($slug), $expiresAt);
        $catalog = Cache::get($key);

        $key = $this->keygen($request->url() . "-episodes-catalog-" . implode($request->all()));
        if( ! Cache::has($key)) Cache::put($key, $this->episodeController->episodes_catalog($catalog), $expiresAt);
        $episodes = Cache::get($key);

		$this->setSEO($catalog->name);
        
        return view('episodes.catalog', [
        	'episodes' => $episodes, 
        	'catalog' => $catalog
        	]);

    }


	public function program(Request $request, $slug){

        $expiresAt = Carbon::now()->addMinutes(15);

        $key = $this->keygen("program-" . $slug);
        if( ! Cache::has($key)) Cache::put($key, $this->programController->program_by_slug($slug), $expiresAt);
        $program = Cache::get($key);
        if( ! $program->exists ) return redirect('/');
        $program = $this->programController->get($program);

		$this->setSEO("", $invet = false, $program, null);

        return view('programs.show', ['program' => $program]);
    }


	public function episode(Request $request, $slug_program, $slug_episode){

		$playlist = $this->playlistController->get();

        $expiresAt = Carbon::now()->addMinutes(15);        

		$key = $this->keygen("episode-" . $slug_episode);
        if( ! Cache::has($key)) Cache::put($key, $this->episodeController->episode_by_slug($slug_episode), $expiresAt);
        $episode = Cache::get($key);
        if( ! $episode->exists ) return redirect('/');
        $episode = $this->episodeController->get($episode);

        $key = $this->keygen("program-" . $slug_program);
        if( ! Cache::has($key)) Cache::put($key, $this->programController->program_by_slug($slug_program), $expiresAt);
        $program = Cache::get($key);
        if( ! $program->exists ) return redirect('/');
        $program = $this->programController->get($program);

		$this->setSEO("", $invet = false, null, $episode);

		return view('episodes.show', [
			'program' => $program, 
			'episode' => $episode, 
			'playlist' => $playlist
			]);
		
    }


    public function setSEO($description = "", $invet = false, $program = null, $episode = null){

		$title = "Podflix";
    	$url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    	if($episode != null){
    		$program_name = $episode->program->name;
    		$episode_title = $episode->title;
    		$description = $episode_title . " - " . $program_name;
    		SEOMeta::addKeyword($program_name);
    	}
    	if($program != null){
    		$program_name = $program->name;
    		$description = $program_name;
    		SEOMeta::addKeyword($program_name);
    	}

		SEOMeta::setTitle($description . " - " . $title);
    	if($invet) SEOMeta::setTitle($title . " - " . $description);

        SEOMeta::setDescription($description);
        SEOMeta::setCanonical($url);
        SEOMeta::addKeyword("podcasts");
        SEOMeta::addKeyword("podcast");
        SEOMeta::addKeyword("podcasts brasileiros");
        SEOMeta::addKeyword("nerdcast");

        OpenGraph::setDescription($description);
        OpenGraph::setTitle($title);
        OpenGraph::setUrl($url);
        OpenGraph::addProperty('type', 'articles');
        OpenGraph::addImage('/images/logo.png', ['height' => 300, 'width' => 300]);

        //Twitter::setTitle($title);
        //Twitter::setSite('@PodflixBrasil');

    }


    public function feedRequestProgramCreate(){


        $this->setSEO("Podflix - Cadastrar Novo Programa", $invet = false, null, null);

        return view('feed.request.program.create');

    }


    

    public function userProgramRequestCreate(Request $request){

        $this->userProgramRequestController->create($request);
        
        $this->setSEO("Podflix - Cadastrar Novo Programa", $invet = false, null, null);

        return Redirect::back()->with("status", true);

    }


}
