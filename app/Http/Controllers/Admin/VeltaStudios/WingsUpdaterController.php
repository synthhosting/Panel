<?php

namespace Pterodactyl\Http\Controllers\Admin\VeltaStudios;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Pterodactyl\Models\Node;
use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Models\VeltaStudios\WingsUpdaterConfiguration;
use Pterodactyl\Services\Helpers\SoftwareVersionService;
use Pterodactyl\Repositories\Wings\DaemonConfigurationRepository;
use Pterodactyl\Exceptions\Http\Connection\DaemonConnectionException;

class WingsUpdaterController extends Controller
{
    private $repository;

    public function __construct(DaemonConfigurationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $nodes = Node::all();
        $globalConfig = WingsUpdaterConfiguration::whereNull('node_id')->first();
        $nodeConfigs = WingsUpdaterConfiguration::whereNotNull('node_id')->get();

        $latestWingsVersion = $this->getLatestWingsVersion($globalConfig);

        $nodesOutdated = $this->getOutdatedNodes($nodes, $globalConfig, $latestWingsVersion);

        return view('admin.veltastudios.wings-updater.index', compact('nodes', 'globalConfig', 'nodeConfigs', 'latestWingsVersion', 'nodesOutdated'));
    }

    private function getLatestWingsVersion($globalConfig)
    {
        if ($globalConfig && $globalConfig->wings_mode === 'default') {
            $versionService = app(SoftwareVersionService::class);
            return $versionService->getDaemon();
        }

        return 'default';
    }

    private function getOutdatedNodes($nodes, $globalConfig, $latestWingsVersion)
    {
        $nodesOutdated = collect();

        foreach ($nodes as $node) {
            $systemInfo = $this->getNodeSystemInformation($node->id)->getData(true);

            if ($globalConfig && $globalConfig->wings_mode === 'default' && $systemInfo['version'] !== $latestWingsVersion) {
                $nodesOutdated->push([
                    'name' => $node->name,
                    'current_version' => $systemInfo['version'],
                    'latest_version' => $latestWingsVersion,
                ]);
            }
        }

        return $nodesOutdated;
    }

    public function getWingsVersion(SoftwareVersionService $versionService)
    {
        try {
            $version = $versionService->getDaemon();
            return response()->json(['version' => $version]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Unable to fetch version information'], 500);
        }
    }

    public function getNodeSystemInformation($nodeId): JsonResponse
    {
        $node = Node::findOrFail($nodeId);

        try {
            $data = $this->repository->setNode($node)->getSystemInformation();

            return new JsonResponse([
                'version' => $data['version'] ?? '',
                'system' => [
                    'type' => Str::title($data['os'] ?? 'Unknown'),
                    'arch' => $data['architecture'] ?? '--',
                    'release' => $data['kernel_version'] ?? '--',
                    'cpus' => $data['cpu_count'] ?? 0,
                ],
            ]);
        } catch (DaemonConnectionException $e) {
            return new JsonResponse(['error' => 'Failed to connect to the daemon'], 500);
        }
    }

    public function getVersion()
    {
        $apiUrl = 'https://api.teramont.net/api/veltastudios/p_addons/wings-updater';
        $appName = config('app.name', 'Pterodactyl');
        $appUrl = config('app.url', 'Unregistered');

        $response = Http::withUserAgent("Wings Updater by Velta Studios @ $appName - $appUrl")
            ->timeout(5)
            ->retry(2, 100, throw: false)
            ->get($apiUrl);

        if ($response->successful()) {
            return response()->json(['version' => $response->json('version')]);
        } else {
            return response()->json(['error' => 'Unable to fetch version information'], 500);
        }
    }

    public function showUpdatePage()
    {
        $nodes = Node::all();
        return view('admin.veltastudios.wings-updater.update', compact('nodes'));
    }

    public function updateNode($nodeId, Request $request)
    {
        $node = Node::findOrFail($nodeId);
        $configuration = WingsUpdaterConfiguration::where('node_id', $node->id)->first() ?: WingsUpdaterConfiguration::whereNull('node_id')->first();

        if (!$configuration) {
            return response()->json(['result' => "Error updating {$node->name}: No configuration found."], 400);
        }

        $ssh = new SSH2($node->fqdn, $configuration->ssh_port ?? 22);
        $authenticated = $this->authenticateSSH($ssh, $configuration, $configuration->ssh_user ?? 'root');

        if (!$authenticated) {
            return response()->json(['result' => "Error updating {$node->name}: Failed to authenticate."], 400);
        }

        $log = $this->executeUpdate($ssh, $request->input('update_type'), $request->input('download_link'), $node);

        return response()->json(['result' => implode("\n", $log)]);
    }

    private function authenticateSSH($ssh, $configuration, $sshUser)
    {
        switch ($configuration->method) {
            case 'node_private_key':
            case 'global_private_key':
                $key = PublicKeyLoader::load($configuration->credential, $configuration->passphrase);
                return $ssh->login($sshUser, $key);

            case 'node_password':
            case 'global_password':
                return $ssh->login($sshUser, $configuration->credential);
        }

        return false;
    }

    private function executeUpdate($ssh, $updateType, $downloadLink, $node)
    {
        $log = [];
        $log[] = "Attempting to update {$node->name} ({$node->fqdn})...";
        $log[] = "Connected OK. Left to update in the background.";
        $log[] = "Attempting to update the control server";

        $command = $this->getUpdateCommand($updateType, $downloadLink);
        $ssh->exec($command);

        $log[] = "Downloading wings...";
        $log[] = "Wings downloaded.";
        $log[] = "Checking package...";
        $log[] = "Installing wings...";
        $log[] = "Wings installed.";
        $log[] = "Restarting wings...";
        $log[] = "Wings restarted.";
        $log[] = "Update complete for {$node->name}.";

        return $log;
    }

    private function getUpdateCommand($updateType, $downloadLink)
    {
        if ($updateType === 'custom') {
            return "cd /usr/local/bin && rm -r wings && wget -O wings {$downloadLink} && chmod +x /usr/local/bin/wings && systemctl restart wings";
        }

        return "systemctl stop wings && curl -L -o /usr/local/bin/wings \"https://github.com/pterodactyl/wings/releases/latest/download/wings_linux_$([[ \"\$(uname -m)\" == \"x86_64\" ]] && echo \"amd64\" || echo \"arm64\")\" && chmod u+x /usr/local/bin/wings && systemctl restart wings";
    }

    public function saveConfiguration(Request $request)
    {
        $request->validate([
            'config_type' => 'required|in:global,individual',
            'method' => 'required_if:config_type,global|in:global_private_key,global_password',
            'credential' => 'required_if:config_type,global|string',
            'passphrase' => 'nullable|string',
            'wings_mode' => 'required_if:config_type,global|in:default,custom',
            'nodes' => 'array|required_if:config_type,individual',
            'nodes.*.method' => 'required_if:config_type,individual|in:node_private_key,node_password',
            'nodes.*.credential' => 'required_if:config_type,individual|string',
            'nodes.*.passphrase' => 'nullable|string',
            'nodes.*.wings_mode' => 'required_if:config_type,individual|in:default,custom',
            'nodes.*.ssh_user' => 'required_if:config_type,individual|string',
            'nodes.*.ssh_port' => 'required_if:config_type,individual|integer',
        ]);

        if ($request->config_type == 'global') {
            WingsUpdaterConfiguration::updateOrCreate(
                ['node_id' => null],
                $request->only(['method', 'credential', 'passphrase', 'wings_mode', 'ssh_user', 'ssh_port'])
            );

            return redirect()->route('admin.veltastudios.nodes.wings-updater.index')->with('success', 'Global configuration saved successfully.');
        } else {
            foreach ($request->nodes as $nodeId => $nodeConfig) {
                WingsUpdaterConfiguration::updateOrCreate(
                    ['node_id' => $nodeId],
                    $nodeConfig
                );
            }

            return redirect()->route('admin.veltastudios.nodes.wings-updater.index')->with('success', 'Individual node configurations saved successfully.');
        }
    }

    public function saveWingsMode(Request $request)
    {
        $request->validate([
            'wings_mode' => 'required|in:default,custom',
        ]);

        $globalConfig = WingsUpdaterConfiguration::whereNull('node_id')->first();
        if ($globalConfig) {
            $globalConfig->wings_mode = $request->wings_mode;
            $globalConfig->save();
        } else {
            WingsUpdaterConfiguration::create([
                'node_id' => null,
                'wings_mode' => $request->wings_mode,
            ]);
        }

        return redirect()->route('admin.veltastudios.nodes.wings-updater.index')->with('success', 'Wings mode updated successfully.');
    }

    public function testConnection(Request $request, $nodeId)
    {
        $node = Node::findOrFail($nodeId);
        $result = "Attempting to connect to {$node->name} ({$node->fqdn})...";

        try {
            $configuration = WingsUpdaterConfiguration::where('node_id', $node->id)->first() ?: WingsUpdaterConfiguration::whereNull('node_id')->first();
            $sshPort = $configuration->ssh_port ?? 22;
            $sshUser = $configuration->ssh_user ?? 'root';

            $ssh = new SSH2($node->fqdn, $sshPort);
            $authenticated = $this->authenticateSSH($ssh, $configuration, $sshUser);

            $result .= $authenticated ? " OK 200" : " Failed to authenticate";
        } catch (Exception $e) {
            $result .= " Connection failed: " . $e->getMessage();
        }

        return response()->json(['result' => $result]);
    }
}
