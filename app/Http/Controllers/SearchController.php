<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

use App\Episode;
use App\Program;


class SearchController extends Controller
{

    public function episodes(Request $request)
    {
        $queryString = $request->input('q');

        Log::debug("Searching for episodes q: {$queryString}");

        $episodes = Episode::with('program.logo')->where('title', 'LIKE', "%$queryString%")->orderBy('played_count','desc')->paginate(10);

        //dd($episodes);

        return $episodes;
    }

    public function programs(Request $request)
    {
        $queryString = $request->input('q');

        Log::debug("Searching for programs q: {$queryString}");

        $programs = Program::with('logo')->where('name', 'LIKE', "%$queryString%")->orderBy('episodes_count','desc')->paginate(5);

        //dd($programs);

        return $programs;
    }
    
}
