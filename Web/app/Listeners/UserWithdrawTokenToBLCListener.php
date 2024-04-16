<?php

namespace App\Listeners;

use App\Contracts\ClaimToken;
use App\Services\BLCGatewayService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserWithdrawTokenToBLCListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ClaimToken $event
     *
     * @return void
     */
    public function handle(ClaimToken $event)
    {
        app(BLCGatewayService::class)->withdraw($event->amount(), $event->transactionId(), $event->address());
    }
}
