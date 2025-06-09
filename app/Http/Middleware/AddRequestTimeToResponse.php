<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddRequestTimeToResponse
{
    public function handle(Request $request, Closure $next): Response
    {

        $response = $next($request);

        if ($response instanceof JsonResponse) {

            $response->setData(array_merge(
                $response->getData(true),
                ['request_time' => microtime(true) - LARAVEL_START],
            ));

        }

        return $response;

    }
}
