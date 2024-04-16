<?php

namespace App\Exports;

use App\Services\Export\Exportable;

class Ticket extends Exportable
{

    protected $data;
    protected $user;

    public function __construct($data,$user)
    {
        $this->data = $data;
        $this->user = $user;
    }

    protected function view()
    {
        return view('mails.send_ticket', [
            'ticket' => $this->data,
            'user' => $this->user,
        ]);

    }
}
