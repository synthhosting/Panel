<?php
namespace Pterodactyl\Console\Commands\Server;

use Exception;
use Illuminate\Console\Command;
use Pterodactyl\Models\EggVariable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Pterodactyl\Models\ServerVariable;
use Pterodactyl\Models\InstalledRustPlugin;
use Pterodactyl\Repositories\Wings\DaemonFileRepository;

class CheckRustPluginVersionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'p:server:rust-plugin-versions';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if any updates have become available for install rust plugins with automatic updating enabled.';
    /**
     * CheckRustPluginVersionsCommand constructor.
     */
    public function __construct(
        private DaemonFileRepository $fileRepository
    ) {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach(InstalledRustPlugin::where('update', true)->get() as $plugin) {
            try {
                $response = Http::get('https://umod.org/plugins/search.json?page=1&sort=latest_release_at&categories=rust&query=' . $plugin->name);
            } catch(Exception) {
                Log::error('Issue connecting to umod API, plugin update failed.');
            }

            if ($response['data'][0]['name'] === $plugin->name) {
                if ($plugin->version !== $response['data'][0]['latest_release_version_formatted']) {

                    $framework = ServerVariable::where('server_id', $plugin->server->id)->where('variable_id', EggVariable::where('egg_id', $plugin->server->egg_id)->where('env_variable', 'FRAMEWORK')->first()->id)->first()->variable_value;

                    $this->fileRepository->setServer($plugin->server)->pull(
                        $response['data'][0]['download_url'],
                        $framework === 'oxide' ? '/oxide/plugins' : '/carbon/plugins'
                    );

                    $plugin->update([
                        'version' => $response['data'][0]['latest_release_version_formatted'],
                    ]);
                }
            }
        }
        return Command::SUCCESS;
    }
}