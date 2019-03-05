<?php

namespace App\Http\Controllers;

use App\Playlist;
use App\User;
use App\Episode;

use Illuminate\Http\Request;

use Requests;
use Auth;
use Log;

class PlaylistController extends Controller
{

    public function get() {

        //Log::debug("playlist get user playlist");

        $playlist = array();

        if (Auth::check()) {
            try {

                $user = User::find(Auth::user()->id);

                $playlists = $user->playlists()->get();

                //dd($playlists->count());

                foreach ($playlists as $item) {
                    $item->episode->link = $item->episode->program->slug."/".$item->episode->slug;
                    $playlist[] = (object) $item->episode;
                }

                //Log::debug("playlist GET OK!");

            } catch (Exception $e) {
                //throw new Exception($e);
                //Log::error($e);
            }
        }

        return $playlist;

    }


    public function add(Request $request) {
        $episode_id = $request->input('episode_id');

        //Log::debug("playlist add episode_id: {$episode_id}");

        if (Auth::check()) {
            try {

                $playlist = Playlist::Create([
                    'episode_id' => $episode_id,
                    'user_id' => Auth::user()->id,
                ]);

                //Log::debug("playlist ADD OK! {$playlist}");

            } catch (Exception $e) {
                //throw new Exception($e);
                //Log::error($e);
            }
        }
    }

    public function remove(Request $request) {
        $episode_id = $request->input('episode_id');

        //Log::debug("playlist episode_id: {$episode_id}");

        if (Auth::check()) {
            try {

                $deletedRows = Playlist::where('episode_id', $episode_id)->where('user_id', Auth::user()->id)->delete();

                //Log::debug("playlist REMOVE OK! {$deletedRows}");

            } catch (Exception $e) {
                //throw new Exception($e);
                //Log::error($e);
            }
        }
    }



}
