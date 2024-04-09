<?php

namespace App\Contracts;

interface ClaimToken
{
    /**
     * ID of user claim
     *
     * @return string
     */
    public function userId();

    /**
     * Recipient's token wallet address
     *
     * @return string
     */
    public function address();

    /**
     * Transaction ID
     *
     * @return string
     */
    public function transactionId();

    /**
     * Withdraw amount
     *
     * @return int
     */
    public function amount();
}
