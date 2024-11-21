<?php

namespace Pterodactyl\Console\Commands\Server;

use DateTimeZone;
use Carbon\Carbon;
use Pterodactyl\Models\Wipe;
use Illuminate\Console\Command;
use Pterodactyl\Jobs\WipeServerJob;
use Pterodactyl\Repositories\Wings\DaemonPowerRepository;
use Pterodactyl\Repositories\Wings\DaemonCommandRepository;

class RustWipeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'p:server:wipe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Wipes rust servers on schedule.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(protected DaemonCommandRepository $commandRepository, protected DaemonPowerRepository $powerRepository)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $wipes = Wipe::all();
        foreach($wipes->filter(function ($wipe) { return !$wipe->ran_at || $wipe->repeat; }) as $wipe) {
            if ($wipe->server) {
                if ($wipe->server->status !== 'suspended') {
                    try {
                        $now = new Carbon(Carbon::now(), new DateTimeZone($wipe->server->timezone ?? DateTimeZone::listIdentifiers(DateTimeZone::ALL)[0]));
                        foreach($wipe->commands as $command) {
                            if ($now->copy()->addMinutes($command->time)->startOfMinute() == Carbon::parse($wipe->time)->startOfMinute()) {
                                $this->commandRepository->setServer($wipe->server)->send($command->command);
                            }
                        }
                        if ($wipe->time <= $now) {
                            $this->powerRepository->setServer($wipe->server)->send('stop');
                            dispatch(new WipeServerJob($wipe->server, $wipe->toArray()))->delay(Carbon::now()->addMinute());
                            $wipe->update([
                                'ran_at' => Carbon::now(),
                            ]);
                            if ($wipe->repeat) {
                                $wipe->update([
                                    'time' => Carbon::parse($wipe->time)->addWeek(),
                                ]);
                            }
                        }
                    } catch(\Exception) {
                    }
                }
            } else {
                $wipe->delete();
            }
        }

        return 0;
    }
}