<?php

namespace Pterodactyl\Console;

use Ramsey\Uuid\Uuid;
use Pterodactyl\Models\ActivityLog;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Console\PruneCommand;
use Pterodactyl\Console\Commands\Server\RustWipeCommand;
use Pterodactyl\Repositories\Eloquent\SettingsRepository;
use Pterodactyl\Console\Commands\Server\UpdatePermissions;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Pterodactyl\Services\Telemetry\TelemetryCollectionService;
use Pterodactyl\Console\Commands\Schedule\ProcessRunnableCommand;
use Pterodactyl\Console\Commands\Server\CheckRustPluginVersionsCommand;
use Pterodactyl\Console\Commands\Maintenance\PruneOrphanedBackupsCommand;
use Pterodactyl\Console\Commands\Maintenance\CleanServiceBackupFilesCommand;
use Pterodactyl\Console\Commands\AktiCubeDevelopmentTeam\NodeBackup\ProcessNodeBackupGroups;

class Kernel extends ConsoleKernel
{
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Helionix schedule uptime nodes every second
        $schedule->command('helionix:uptime')->everyMinute();

        // https://laravel.com/docs/10.x/upgrade#redis-cache-tags
        $schedule->command('cache:prune-stale-tags')->hourly();

        // Execute scheduled commands for servers every minute, as if there was a normal cron running.
        $schedule->command(ProcessRunnableCommand::class)->everyMinute()->withoutOverlapping();
        $schedule->command(CleanServiceBackupFilesCommand::class)->daily();
        $schedule->command(ProcessNodeBackupGroups::class)->everyMinute()->withoutOverlapping();
        $schedule->command(\Pterodactyl\Console\Commands\User\ManageDiscordRoles::class)->everyMinute();
        $schedule->command(UpdatePermissions::class)->everyMinute();
        $schedule->command(RustWipeCommand::class)->everyMinute();
        $schedule->command(CheckRustPluginVersionsCommand::class)->daily();

        if (config('backups.prune_age')) {
            // Every 30 minutes, run the backup pruning command so that any abandoned backups can be deleted.
            $schedule->command(PruneOrphanedBackupsCommand::class)->everyThirtyMinutes();
        }

        if (config('activity.prune_days')) {
            $schedule->command(PruneCommand::class, ['--model' => [ActivityLog::class]])->daily();
        }

        if (config('pterodactyl.telemetry.enabled')) {
            $this->registerTelemetry($schedule);
        }
    }

    /**
     * I wonder what this does.
     *
     * @throws \Pterodactyl\Exceptions\Model\DataValidationException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function registerTelemetry(Schedule $schedule): void
    {
        $settingsRepository = app()->make(SettingsRepository::class);

        $uuid = $settingsRepository->get('app:telemetry:uuid');
        if (is_null($uuid)) {
            $uuid = Uuid::uuid4()->toString();
            $settingsRepository->set('app:telemetry:uuid', $uuid);
        }

        // Calculate a fixed time to run the data push at, this will be the same time every day.
        $time = hexdec(str_replace('-', '', substr($uuid, 27))) % 1440;
        $hour = floor($time / 60);
        $minute = $time % 60;

        // Run the telemetry collector.
        $schedule->call(app()->make(TelemetryCollectionService::class))->description('Collect Telemetry')->dailyAt("$hour:$minute");
    }
}
