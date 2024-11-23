<?php

namespace Pterodactyl\Http\Controllers\Api\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class KnowledgebaseController extends ClientApiController
{
    /**
     * @var \Prologue\Alerts\AlertsMessageBag
     */
    private $alert;

    /**
     * KnowledgebaseController constructor.
     */
    public function __construct()
    {
    }

    public function categories(Request $request)
    {
        $categories = DB::table('knowledgebase_category')->get();

        return response()->json($categories);
    }

    public function topics(Request $request)
    {
        $topics = DB::table('knowledgebase')->get();

        return response()->json($topics);
    }

    public function topic(Request $request, $id)
    {
        $id = (int) $id;

        $topic = DB::table('knowledgebase')
        ->where('id', '=', $id)
        ->first();

        if (!isset($topic)) {
            throw new NotFoundHttpException();
        }

        return response()->json($topic);
    }

    public function topicsfrom(Request $request, $id)
    {
        $id = (int) $id;

        $topics = DB::table('knowledgebase')
        ->where('category', '=', $id)
        ->get();

        return response()->json($topics);
    }
}
