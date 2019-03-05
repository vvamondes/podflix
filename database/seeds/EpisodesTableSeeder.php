<?php

use Illuminate\Database\Seeder;
use App\Program;
use App\Episodes;
use App\Http\Controllers\FeedController;

class EpisodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {

        $hour = date('H', time());
        $limit = 3;
        if( $hour >= 1 && $hour <= 7) {
            $limit = 900;
        }

        $programs = Program::orderBy('checked_at','asc')->orderBy('id','asc')->take(100)->get();
        foreach ($programs as $index=>$program) {
            $feedController = new FeedController;
            $feedController->updateProgramCheckedAt($program);
            try {
                Log::debug("EpisodesTableSeeder " . $program->feed . " INICIO");
                Log::debug("EpisodesTableSeeder " . $hour . "h " . $limit . " LIMIT");
                $feedController->loadEpisodesFromProgram($program, $limit);
                Log::debug("EpisodesTableSeeder " . $program->feed . " FIM");
            } catch (Exception $e) {
                // All other exceptions
                Log::debug("EpisodesTableSeeder " . $program->feed . " " . $e);
            }
        }

    }
}
