<?php

namespace Pterodactyl\Jobs;

use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdaterJob extends Job implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use SerializesModels;

    public int $timeout = 10 * 60;

    public function __construct(public string $url, public bool $rollbackMigrations = false, public bool $doBackup = true)
    {
        $this->url = $url;
        $this->rollbackMigrations = $rollbackMigrations;
        $this->doBackup = $doBackup;
    }

    private function log(string $line)
    {
        $oldData = '';
        if (Cache::has('itsvic.updater.logData')) {
            $oldData = Cache::get('itsvic.updater.logData') . "\n";
        }
        Cache::set('itsvic.updater.logData', $oldData . $line);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (str_starts_with($this->url, 'http')) {
            $ext = pathinfo($this->url, PATHINFO_EXTENSION);
            if ($ext === 'gz') {
                $ext = 'tar.gz'; // assume it's tar.gz if the ext is gz
            }
            $archivePath = '/tmp/panelUpdate.' . $ext;
            $this->log('Downloading file ' . $this->url . ' to ' . $archivePath . '...');
            if (!file_put_contents($archivePath, file_get_contents($this->url))) {
                Cache::delete('itsvic.updater.isUpdating');
                Cache::set('itsvic.updater.errorReason', 'Failed to download the archive.');

                return;
            }
        } else {
            // assume the URL passed is an uploaded file
            $archivePath = $this->url;
        }

        $this->log('Extracting the archive...');
        exec('rm -rf /tmp/pteroUpdate'); // cheap ik but i dont care
        mkdir('/tmp/pteroUpdate', 0700);
        $output = null;
        $retval = null;
        exec(sprintf('bsdtar xf %s -C /tmp/pteroUpdate', $archivePath), $output, $retval);
        if ($retval != 0) {
            Cache::delete('itsvic.updater.isUpdating');
            Cache::set('itsvic.updater.errorReason', 'Failed to extract the archive.');

            return;
        }

        if (!unlink($archivePath)) {
            $this->log("Failed to delete the archive file. That's ok - it's just annoying.");
        }

        if (!file_exists('/tmp/pteroUpdate/artisan') && !file_exists('/tmp/pteroUpdate/package.json')) {
            Cache::delete('itsvic.updater.isUpdating');
            Cache::set('itsvic.updater.errorReason', 'The archive is not a Pterodactyl panel archive.');

            return;
        }

        if (!file_exists('/tmp/pteroUpdate/updaterSanityTest.php')) {
            $this->log('The updater does not appear to be installed in the updated panel. Expect to see a 404 Not Found page.');
            copy(base_path('updaterSanityTest.php'), '/tmp/pteroUpdate/updaterSanityTest.php');
        }

        chmod('/tmp/pteroUpdate/bootstrap/cache', 0755);
        foreach (glob('/tmp/pteroUpdate/storage/*') as $a) {
            chmod($a, 0755);
        }

        copy(base_path('.env'), '/tmp/pteroUpdate/.env');
        $this->log('Copied .env from current install.');

        $this->log('Installing dependencies...');
        $oldWD = getcwd();
        chdir('/tmp/pteroUpdate');
        exec('composer install --no-dev --optimize-autoloader');

        if (file_exists('/tmp/pteroUpdate/public/assets/manifest.json')) {
            $this->log('Found JS assets, not building panel.');
        } else {
            $this->log('Building the client frontend...');
            exec('yarn install --no-cache && yarn build:production');
        }

        if ($this->rollbackMigrations) {
            $this->log('Rolling back previous migrations...');
            exec('yes | php artisan migrate:rollback');
        }

        $this->log('Migrating database...');
        exec('php artisan migrate --seed --force');

        $this->log('Running a sanity test...');
        $output = null;
        $retval = null;
        exec(PHP_BINARY . ' updaterSanityTest.php', $output, $retval);
        if ($retval !== 0) {
            Cache::delete('itsvic.updater.isUpdating');
            Cache::set('itsvic.updater.errorReason', 'The updated panel failed sanity checks.');
            chdir($oldWD);

            return;
        }

        chdir($oldWD);

        if ($this->doBackup) {
            $this->log('Backing up the current panel...');
            $install = base_path();
            $backup = $install . '_old.tar.gz';
            // tar caf /ptero_old.tar.gz -C /ptero .
            if (file_exists($backup)) {
                unlink($backup); // remove the old backup, if any
            }
            $output = null;
            $retval = null;
            exec('tar caf ' . $backup . ' -C ' . $install . ' .', $output, $retval);
            if ($retval !== 0) {
                Cache::delete('itsvic.updater.isUpdating');
                Cache::set('itsvic.updater.errorReason', 'Failed to backup the current panel.');
                return;
            }
        }

        $this->log('Upgrading the current panel in-place...');
        exec(sprintf('rsync -av /tmp/pteroUpdate/. %s/. --delete-after', $install));

        // we're done, clean up
        Cache::delete('itsvic.updater.isUpdating');
    }
}
