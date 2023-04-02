<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Str;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $path = Str::start($request->path(), '/');
            if (Str::startsWith($path, ['/admin'])) {
                return route('admin.login');
            }

            if (Str::startsWith($path, ['/comercial'])) {
                return route('comercial.login');
            }
            
            if (Str::startsWith($path, ['/instituicao'])) {
                return route('instituicao.login');
            }
            
            return url('/');
        }
    }
}
