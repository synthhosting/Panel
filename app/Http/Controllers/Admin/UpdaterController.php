<?php

namespace Pterodactyl\Http\Controllers\Admin;

use Illuminate\View\View;
use Pterodactyl\Jobs\UpdaterJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Prologue\Alerts\AlertsMessageBag;
use Illuminate\View\Factory as ViewFactory;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Http\Requests\Admin\Updater\UpdateFormRequest;
use Pterodactyl\Http\Requests\Admin\Updater\RestoreFormRequest;

class UpdaterController extends Controller
{
    public function __construct(
        protected AlertsMessageBag $alert,
        protected ViewFactory $view,
    ) {
    }

    public function index(): View
    {
        return $this->view->make('admin.updater.index', [
            'canRestore' => file_exists(base_path() . '_old.tar.gz'),
        ]);
    }

    public function log(): View|RedirectResponse
    {
        if (Cache::has('itsvic.updater.isUpdating')) {
            return $this->view->make('admin.updater.log', [
                'logData' => Cache::get('itsvic.updater.logData'),
            ]);
        } else {
            if (Cache::has('itsvic.updater.errorReason')) {
                $this->alert->danger('The panel has failed to update: ' . Cache::get('itsvic.updater.errorReason'))->flash();
            } else {
                $this->alert->success('The panel has finished updating and has passed sanity checks.')->flash();
            }

            return redirect()->route('admin.updater');
        }
    }

    private function logData(string $line)
    {
        $oldData = '';
        if (Cache::has('itsvic.updater.logData')) {
            $oldData = Cache::get('itsvic.updater.logData') . "\n";
        }
        Cache::set('itsvic.updater.logData', $oldData . $line);
    }

    public function restore(RestoreFormRequest $request): RedirectResponse
    {
        $path = base_path() . '_old.tar.gz';
        if (!file_exists($path)) {
            $this->alert->danger('')->flash();

            return redirect()->route('admin.updater');
        }
        Cache::delete('itsvic.updater.errorReason');
        Cache::delete('itsvic.updater.logData');
        Cache::set('itsvic.updater.isUpdating', 'true');
        $this->logData('Waiting for the rollback job to start...');
        UpdaterJob::dispatch($path, $request->has('rollback'), false);

        return redirect()->route('admin.updater.log');
    }

    public function update(UpdateFormRequest $request): RedirectResponse
    {
        if ($request->hasFile('archive_file')) {
            // use the file
            $file = $request->file('archive_file');
            $file = $file->storeAs('update', 'panelUpdate.' . ($file->getMimeType() == 'application/zip' ? 'zip' : 'tar.gz'));
            $url = storage_path('app/' . $file);
        } else {
            $url = $request->normalize()['url'];
        }
        Cache::delete('itsvic.updater.errorReason');
        Cache::delete('itsvic.updater.logData');
        Cache::set('itsvic.updater.isUpdating', 'true');
        $this->logData('Waiting for the update job to start...');
        UpdaterJob::dispatch($url);

        return redirect()->route('admin.updater.log');
    }
}
