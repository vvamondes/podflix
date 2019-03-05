<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserProgramRequest;
use Auth;
use Log;

class UserProgramRequestController extends Controller
{
    
    public function create(Request $request)
    {
        $userProgramRequest = new UserProgramRequest;

        $userProgramRequest->request = "create";

        $userProgramRequest->user_id = Auth::user()->id;
        $userProgramRequest->feed = $request->feed;

        $userProgramRequest->name = $request->name;
        $userProgramRequest->description = $request->description;
        $userProgramRequest->logo = $request->logo;
        $userProgramRequest->site = $request->site;
        
        $userProgramRequest->email = $request->email;
        $userProgramRequest->twitter = $request->twitter;
        $userProgramRequest->facebook = $request->facebook;
        $userProgramRequest->googleplus = $request->googleplus;

        Log::debug("Create UserProgramRequestController " . print_r($userProgramRequest));

        $userProgramRequest->save();
        return true;
    }

}
