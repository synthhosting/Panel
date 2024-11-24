<?php

namespace Pterodactyl\Http\Controllers\Api\Client\Servers;

use Pterodactyl\Models\Server;
use Illuminate\Http\JsonResponse;
use GuzzleHttp\Exception\GuzzleException;
use Pterodactyl\Exceptions\DisplayException;
use Pterodactyl\Repositories\Wings\DaemonSmartSearchRepository;
use Pterodactyl\Http\Controllers\Api\Client\ClientApiController;
use Pterodactyl\Exceptions\Http\Connection\DaemonConnectionException;
use Pterodactyl\Http\Requests\Api\Client\Servers\Files\SmartSearchRequest;

class SmartSearchController extends ClientApiController
{
    /**
     * @param DaemonSmartSearchRepository $smartSearchRepository
     */
    public function __construct(private DaemonSmartSearchRepository $smartSearchRepository)
    {
        parent::__construct();
    }

    /**
     * @param SmartSearchRequest $request
     * @param Server $server
     * @return JsonResponse
     * @throws DisplayException
     */
    public function search(SmartSearchRequest $request, Server $server)
    {
        $this->validate($request, [
            'root' => ['required', 'nullable', 'string'],
            'query' => ['required', 'string', 'min:3'],
        ]);

        try {
            $response = $this->smartSearchRepository->setServer($server)->smartSearch(trim($request->input('root', '/')), $request->input('query'));
        } catch (GuzzleException|DaemonConnectionException $e) {
            throw new DisplayException('Failed to search to the query. Please try again later...');
        }

        $results = json_decode($response->getBody(), true)['result'] ?? [];
        if (count($results) > 30) {
            throw new DisplayException('We found too many results for the search term. Please be more specific to be able to see all the results.');
        }

        return response()->json([
            'results' => $results,
        ]);
    }
}
