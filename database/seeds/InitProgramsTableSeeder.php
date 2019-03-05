<?php

use App\Program;
use App\ProgramMeta;
use App\ProgramSite;
use App\ProgramEmail;
use App\ProgramImage;
use App\ProgramTwitter;
use App\ProgramFacebook;
use App\ProgramGoogleplus;
use Illuminate\Database\Seeder;
use App\Http\Controllers\FeedController;

class InitProgramsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach ($this->programs_migration() as $index=>$program) {

            $program = (object) $program;

                try {
                    Log::debug("InitTableSeeder " . $program->feed . " INICIO");

                    $new_program = new Program;
                    $new_program->id = $program->id;
                    $new_program->name = $program->nome;
                    $new_program->feed = $program->feed;
                    $new_program->save();

                    $program_image = new ProgramImage;
                    $program_image->program_id = $program->id;
                    $program_image->save();

                    if ($program->email != ""){
                        ProgramEmail::create([
                            'program_id' => $program->id,
                            'name' => $program->email,
                        ]);
                    }

                    if ($program->site != ""){
                        ProgramSite::create([
                            'program_id' => $program->id,
                            'name' => $program->site,
                        ]);
                    }

                    if ($program->facebook != ""){
                        $facebooks = explode(" ", $program->facebook);
                        foreach ($facebooks as $facebook) {
                            ProgramFacebook::create([
                                'program_id' => $program->id,
                                'name' => $facebook,
                            ]);
                        }
                    }

                    if ($program->twitter != ""){
                        $twitters = explode(" ", $program->twitter);
                        foreach ($twitters as $twitter) {
                            ProgramTwitter::create([
                                'program_id' => $program->id,
                                'name' => $twitter,
                            ]);
                        }
                    }

                    if ($program->googleplus != ""){
                        $googlepluss = explode(" ", $program->googleplus);
                        foreach ($googlepluss as $googleplus) {
                            ProgramGoogleplus::create([
                                'program_id' => $program->id,
                                'name' => $googleplus,
                            ]);
                        }
                    }

                    Log::debug("InitTableSeeder " . $program->feed . " FIM");
                } catch (Exception $e) {
                    // All other exceptions
                    Log::debug("InitTableSeeder " . $program->feed . " " . $e);
                }
            //}
        }


    }


    /*
     * SELECT feed, id, email, twitter, facebook, googleplus, site FROM feeds
     */

    public function programs_migration()
    {

        return array(
            array('nome' => 'Nerdcast','feed' => 'https://jovemnerd.com.br/feed-nerdcast/','id' => '1','email' => '','twitter' => '','facebook' => '','googleplus' => NULL,'site' => ''),
            array('nome' => 'Matando Robôs Gigantes','feed' => 'http://feed.matandorobosgigantes.com','id' => '2','email' => '','twitter' => 'MRG','facebook' => 'matandorobosgigantes','googleplus' => NULL,'site' => ''),
            array('nome' => 'Braincast','feed' => 'http://feeds.feedburner.com/braincastmp3','id' => '4','email' => '','twitter' => '','facebook' => '','googleplus' => NULL,'site' => ''),
            array('nome' => 'Rapaduracast','feed' => 'http://feeds.feedburner.com/rapaduracast','id' => '5','email' => '','twitter' => '','facebook' => '','googleplus' => NULL,'site' => ''),
            array('nome' => '99vidas',' feed' => 'http://99vidas.com.br/category/99vidas/feed/',' id' => '13',' email' => '',' twitter' => '',' facebook' => '',' googleplus' => null,' site' => ''),
        );
    }

}
