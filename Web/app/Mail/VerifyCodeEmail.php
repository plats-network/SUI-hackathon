<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyCodeEmail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Confirmation code
     *
     * @var string
     */
    protected $code;

    /**
     *
     * @param string $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * @return VerifyEmail
     */
    public function build()
    {
        return $this->subject('Kích hoạt tài khoản Plats')
            ->view('mails.verify', ['confirmation_code' => $this->code]);
    }
}
