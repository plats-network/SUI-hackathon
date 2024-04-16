<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class DebugJsonHttpResponse
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (
            env('API_DEBUG', false)
            && $response instanceof JsonResponse
            && app()->bound('debugbar')
            && app('debugbar')->isEnabled()
        ) {
            $queriesData = $this->sqlFilter(app('debugbar')->getData());

            $responseData = json_decode($response->getContent(), true) + [
                    '_debugbar' => [
                        'total_queries' => count($queriesData),
                        'duplicates'    => collect($queriesData)
                            ->groupBy('sql')
                            ->map(fn ($rows) => $rows->count())
                            ->map(fn ($value, $index) => [
                                'sql'   => $index,
                                'total' => $value,
                            ])
                            ->sortByDesc('total')
                            ->values()
                            ->toArray(),
                        'queries'       => $queriesData,
                    ],
                ];

            $response = $response->setContent(json_encode($responseData));
        }

        return $response;
    }

    /**
     * Get sql queries information.
     *
     * @param array $debugbarData
     *
     * @return array
     */
    protected function sqlFilter(array $debugbarData): array
    {
        $result = Arr::get($debugbarData, 'queries.statements');

        return array_map(fn ($item) => [
            'sql'       => Arr::get($item, 'sql'),
            'duration'  => Arr::get($item, 'duration_str'),
            'stmt_id'   => Arr::get($item, 'stmt_id'),
            'backtrace' => collect(Arr::get($item, 'backtrace'))->mapWithKeys(fn ($item) => [
                $item->index => "{$item->name}:{$item->line}",
            ]),
        ], $result);
    }
}
