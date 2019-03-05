<?php

use Illuminate\Database\Seeder;
use App\Program;
use App\ProgramGoogleplus;
use App\ProgramFacebook;
use App\ProgramTwitter;
use App\ProgramMeta;
use App\ProgramSite;
use App\ProgramEmail;
use App\Http\Controllers\FeedController;

class ProgramsTableSeeder extends Seeder
{
    

    /*
     * MAC OS $ php /Users/vvamondes/podflix-laravel/artisan db:seed --class=ProgramsTableSeeder
     * LINUX $ php /home/vvamondes/Podflix/site/artisan db:seed --class=ProgramsTableSeeder
     */

    public function run()
    {

        $programs = Program::all();
        foreach ($programs as $index=>$program) {
            $feedController = new FeedController;
            try {
                Log::debug("ProgramsTableSeeder " . $program->feed . " INICIO");
                $feedController->updateProgramInfosFromFeed($program, true);
                Log::debug("ProgramsTableSeeder " . $program->feed . " FIM");
            } catch (Exception $e) {
                // All other exceptions
                Log::debug($program->feed . " " . $e);
            }
        }

    }

}
