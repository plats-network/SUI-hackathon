<?php

declare(strict_types=1);

namespace App\Http\UseCases\Api\v1\Campain;

use App\Http\Shared\MakeApiResponse;
use App\Models\MongoDB\Post as Campain;
use Illuminate\Http\JsonResponse;

final class DeleteUseCase
{
    use MakeApiResponse;

    public function handle(Campain $campain): JsonResponse
    {
        $campain->delete();
        //Todo delete tasks

        /*Notification::route('mail', $campain->getAttribute('email'))
            ->notify(new AccountDeleted());*/

        return $this->successResponse('Campain deleted successfully.');
    }
}
