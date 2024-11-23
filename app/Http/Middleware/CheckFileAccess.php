<?php

namespace Pterodactyl\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Pterodactyl\Services\Files\FilesPermissions;
use Pterodactyl\Transformers\Api\Client\FileObjectTransformer;

class CheckFileAccess
{
    public function __construct(private FilesPermissions $filesPermissions)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $server = $request->route()->parameter('server');

        if($user->root_admin) {
            return $next($request);
        }

        if($request->input('file')) {
            $response = $this->filesPermissions->hasAccess($request, $server, $request->input('file'));

            if(!$response) {
                return new Response([
                    'error' => 'You do not have permission to access this file.',
                ], Response::HTTP_FORBIDDEN);
            }
        }

        if($request->input('name') && $request->input('root')) {
            $file = preg_replace('/\/+/', '/', $request->input('root') . '/' . $request->input('name'));

            $response = $this->filesPermissions->hasAccess($request, $server, $file);

            if(!$response) {
                return new Response([
                    'error' => 'You do not have permission to access this file.',
                ], Response::HTTP_FORBIDDEN);
            }
        }

        if($request->input("files") && is_array($request->input("files")[0])) {
            $files = $request->input("files")[0];
            $root = preg_replace('/\/+/', '/', $request->input('root') . '/');

            if(isset($files['from']) && isset($files['to'])) {
                $from = $root . $files['from'];
                $to = $root . $files['to'];

                $fromAccess = $this->filesPermissions->hasAccess($request, $server, $from);
                $toAccess = $this->filesPermissions->hasAccess($request, $server, $to);

                if(!$fromAccess || !$toAccess) {
                    return new Response([
                        'error' => 'You do not have permission to access this file.',
                    ], Response::HTTP_FORBIDDEN);
                }
            } elseif (isset($files['file']) && isset($files['mode'])) {
                $file = $root . $files['file'];

                $fileAccess = $this->filesPermissions->hasAccess($request, $server, $file);

                if(!$fileAccess) {
                    return new Response([
                        'error' => 'You do not have permission to access this file.',
                    ], Response::HTTP_FORBIDDEN);
                }
            }
        }

        if($request->input("location")) {
            $response = $this->filesPermissions->hasAccess($request, $server, $request->input("location"));

            if(!$response) {
                return new Response([
                    'error' => 'You do not have permission to access this file.',
                ], Response::HTTP_FORBIDDEN);
            }
        }

        return $next($request);
    }
}
