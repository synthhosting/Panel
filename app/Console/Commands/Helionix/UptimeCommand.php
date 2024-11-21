<?php

namespace Pterodactyl\Console\Commands\Helionix;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Pterodactyl\Models\Node;

class UptimeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'helionix:uptime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the uptime for online nodes in Redis every second.';

    /**
     * Execute the console command.
    */
    public function handle()
    {
        $nodes = Node::where('uptime', true)->get();

        foreach ($nodes as $node) {
            $key = "helionix:{$node->fqdn}:{$node->daemonListen}";

            if ($this->isNodeOnline($node->fqdn, $node->daemonListen)) {
                if (!Redis::exists($key)) {
                    Redis::set($key, 0);
                }
                Redis::incrby($key, 60);
            } else {
                Redis::del($key);
            }
        }
    }

    /**
     * handle the uptime command.
    */
    private function isNodeOnline($fqdn, $port): bool
    {
        $connection = @fsockopen($fqdn, $port, $errno, $errstr, 5);
        if ($connection) {
            fclose($connection);
            return true;
        }
        return false;
    }
}
