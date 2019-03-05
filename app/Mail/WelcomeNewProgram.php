<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Program;

class WelcomeNewProgram extends Mailable
{
    use Queueable, SerializesModels;

    public $program;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Program $program)
    {
        $this->program = $program;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $subject = "Programa " . $this->program->name . " Cadastrado";

        return $this->view('emails.welcomenewprogram')
                ->with('program', $this->program)
                ->subject($subject);
    }
}
