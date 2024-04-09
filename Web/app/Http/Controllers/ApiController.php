<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\Traits\ApiResponseTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
    use ApiResponseTrait;

    /**
     * @param $message
     * @param int $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseMessage($message, $code = 200)
    {
        return response()->json(['data' => ['message' => $message]], $code);
    }
}
