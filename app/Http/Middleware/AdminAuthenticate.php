<?php
/**
 * Pterodactyl - Panel
 * Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com>.
 *
 * This software is licensed under the terms of the MIT license.
 * https://opensource.org/licenses/MIT
 */

namespace Pterodactyl\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if($request->user()) {
            if(isset($request->user()->getRole)) {
                foreach($request->user()->getRole->toArray() as $key => $row) {
                    if(str_contains($key, 'p_')) {
                        $key = str_replace('p_', '', $key);
                        if(strpos(Route::current()->uri, $key) == true) {
                            $route = $row;
                        }
                    }
                }
                if(!isset($route)) $route = 2;
                if(isset($request->user()->role) && Route::current()->uri === 'admin') $route = 1;
            } else { $route = 0; }
            $method = array_values(json_decode(json_encode(Route::current()->methods), true));
            $method = array_shift($method);
            if($method === "PATCH" || $method === 'POST' || $method === 'DELETE' || $method === 'PUT' || Route::current()->uri == 'admin/permissions/delete/{id}') {
                if($route !== 2) $post = true;
            }
        }

        if (!$request->user() || $route == 0 || isset($post)) {
            throw new AccessDeniedHttpException();
        }

        return $next($request);
    }
}