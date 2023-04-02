<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class OnlyNegotiateJson
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        // return $next($request);
        
        if (!$request->expectsJson()) {
            throw new NotAcceptableHttpException("We only accept requests that expect JSON responses");
        }

        return $next($request);
    }
}
