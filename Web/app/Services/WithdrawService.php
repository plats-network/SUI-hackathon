<?php

namespace App\Services;

use App\Events\UserClaimTokenEvent;
use App\Repositories\WithdrawRepository;
use App\Services\Concerns\BaseService;

class WithdrawService extends BaseService
{
    /**
     * @param \App\Repositories\WithdrawRepository $repository
     */
    public function __construct(WithdrawRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * User request withdraw token
     *
     * @param string $userId
     * @param string $address Recipient's token wallet address
     * @param int $amount
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function claim($userId, $address, $amount = 0)
    {
        $transaction = $this->repository->create([
            'user_id' => $userId,
            'address' => $address,
            'amount'  => $amount,
            'status'  => WITHDRAWN_STATUS_PENDING,
        ]);

        UserClaimTokenEvent::dispatch($userId, $address, $transaction->id, $amount);

        return $transaction;
    }
}
