<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NFTNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $senderName;
    public $nftName;
    public $nftDescription;
    public $nftUrl;

    public function __construct($userName, $senderName, $nftName, $nftDescription, $nftUrl)
    {
        $this->userName = $userName;
        $this->senderName = $senderName;
        $this->nftName = $nftName;
        $this->nftDescription = $nftDescription;
        $this->nftUrl = $nftUrl;
    }

    public function build()
    {
        return $this->markdown('emails.nft_notification');
    }
}
