<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Services\WithdrawService;
use Illuminate\Http\Request;

class Wallet extends ApiController
{
    /**
     * @var \App\Services\WithdrawService
     */
    protected $withdrawService;

    /**
     * @param \App\Services\WithdrawService $withdrawService
     */
    public function __construct(WithdrawService $withdrawService)
    {
        $this->withdrawService = $withdrawService;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function withdraw(Request $request)
    {
        $this->withdrawService->claim($request->user()->id);

        return $this->responseMessage('Done');
    }
}
