<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnlyXhr
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->expectsJson()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }
}
