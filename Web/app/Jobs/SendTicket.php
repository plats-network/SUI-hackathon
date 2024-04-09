<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendTicket as EmailSendTicket;
use Illuminate\Support\Facades\Mail;

class SendTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ticket;
    protected $email;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ticket, $email, $user)
    {
        $this->ticket = $ticket;
        $this->email = $email;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new EmailSendTicket($this->ticket, $this->user));
    }
}
