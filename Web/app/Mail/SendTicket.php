<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class SendTicket extends Mailable
{
    use Queueable, SerializesModels;

    protected $ticket;
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ticket, $user)
    {
        $this->ticket = $ticket;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $event = Task::query()->where('id', $this->ticket->task_id)->first();

        return $this->subject('Ticket Join Event')
            //->view('mails.event.ticket', [
            ->view('mails.send_ticket', [
                'ticket' => $this->ticket,
                'event' => $event,
                'user' => $this->user,
            ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
