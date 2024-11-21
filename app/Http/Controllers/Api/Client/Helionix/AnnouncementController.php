<?php

namespace Pterodactyl\Http\Controllers\Api\Client\Helionix;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Pterodactyl\Http\Controllers\Api\Client\ClientApiController;

class AnnouncementController extends ClientApiController
{
    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request): JsonResponse
    {
        $announcements = DB::table('announcements')->orderBy('updated_at', 'DESC')->get();

        return response()->json([
            'data' => [
                'announcements' => $announcements,
            ],
        ]);
    }
}
