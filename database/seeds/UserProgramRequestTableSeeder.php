<?php

use Illuminate\Database\Seeder;

use App\Http\Controllers\FeedController;
use App\UserProgramRequest;
use Carbon\Carbon;

class UserProgramRequestTableSeeder extends Seeder
{
    /*
     * MAC OS $ php /Users/vvamondes/podflix-laravel/artisan db:seed --class=UserProgramRequestTableSeeder
     * LINUX $ php /home/vvamondes/Podflix/site/artisan db:seed --class=UserProgramRequestTableSeeder
     */

    public function run()
    {

        $yesterday = Carbon::yesterday();
        $limit = 1000;

        $userProgramCreateRequests = UserProgramRequest::whereNull('replied_at')
        ->where('created_at', '>=', $yesterday)
        ->where('request', '=', 'create')
        ->where('status', '=', 'open')
        ->orderBy('created_at','asc')
        ->get();

        //->take(2)

        foreach ($userProgramCreateRequests as $index=>$userProgramCreateRequest) {
            $feedController = new FeedController;
            try {
                Log::debug("UserProgramRequestTableSeeder " . $userProgramCreateRequest->feed . " INICIO");
                $feedController->createProgramsAndEpisodesFromUserProgramRequestId($userProgramCreateRequest, $limit);
                Log::debug("UserProgramRequestTableSeeder " . $userProgramCreateRequest->feed . " FIM");
            } catch (Exception $e) {
                // All other exceptions
                Log::debug($userProgramCreateRequest->feed . " " . $e);
            }
        }
    }

}
