<?php

namespace App\Events;

use App\Contracts\ClaimToken;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserClaimTokenEvent implements ClaimToken
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var string
     */
    public $userId;

    /**
     * @var string
     */
    public $address;

    /**
     * @var string
     */
    public $historyId;

    /**
     * Default all balance
     *
     * @var integer
     */
    public $amount;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userId, $address, $historyId, $amount = 0)
    {
        $this->userId    = $userId;
        $this->address   = $address;
        $this->historyId = $historyId;
        $this->amount    = $amount;
    }

    /**
     * ID of user claim
     *
     * @return string
     */
    public function userId()
    {
        return $this->userId;
    }

    /**
     * Recipient's token wallet address
     *
     * @return string
     */
    public function address()
    {
        return $this->address;
    }

    /**
     * Transaction ID
     *
     * @return string
     */
    public function transactionId()
    {
        return $this->historyId;
    }

    /**
     * Withdraw amount
     *
     * @return int
     */
    public function amount()
    {
        return $this->amount;
    }
}
