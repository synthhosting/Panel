<?php

use Illuminate\Support\Facades\Route;
use Pterodactyl\Http\Controllers\Admin;
use Pterodactyl\Http\Middleware\Admin\Servers\ServerInstalled;

Route::get('/', [Admin\BaseController::class, 'index'])->name('admin.index');

/*
|--------------------------------------------------------------------------
| Location Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/api
|
*/
Route::group(['prefix' => 'api'], function () {
    Route::get('/', [Admin\ApiController::class, 'index'])->name('admin.api.index');
    Route::get('/new', [Admin\ApiController::class, 'create'])->name('admin.api.new');

    Route::post('/new', [Admin\ApiController::class, 'store']);

    Route::delete('/revoke/{identifier}', [Admin\ApiController::class, 'delete'])->name('admin.api.delete');
});

/*
|--------------------------------------------------------------------------
| Location Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/locations
|
*/
Route::group(['prefix' => 'locations'], function () {
    Route::get('/', [Admin\LocationController::class, 'index'])->name('admin.locations');
    Route::get('/view/{location:id}', [Admin\LocationController::class, 'view'])->name('admin.locations.view');

    Route::post('/', [Admin\LocationController::class, 'create']);
    Route::patch('/view/{location:id}', [Admin\LocationController::class, 'update']);
});

/*
|--------------------------------------------------------------------------
| Database Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/databases
|
*/
Route::group(['prefix' => 'databases'], function () {
    Route::get('/', [Admin\DatabaseController::class, 'index'])->name('admin.databases');
    Route::get('/view/{host:id}', [Admin\DatabaseController::class, 'view'])->name('admin.databases.view');

    Route::post('/', [Admin\DatabaseController::class, 'create']);
    Route::patch('/view/{host:id}', [Admin\DatabaseController::class, 'update']);
    Route::delete('/view/{host:id}', [Admin\DatabaseController::class, 'delete']);
});

/*
|--------------------------------------------------------------------------
| Settings Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/settings
|
*/
Route::group(['prefix' => 'settings'], function () {
    Route::get('/', [Admin\Settings\IndexController::class, 'index'])->name('admin.settings');
    Route::get('/mail', [Admin\Settings\MailController::class, 'index'])->name('admin.settings.mail');
    Route::get('/advanced', [Admin\Settings\AdvancedController::class, 'index'])->name('admin.settings.advanced');

    Route::post('/mail/test', [Admin\Settings\MailController::class, 'test'])->name('admin.settings.mail.test');

    Route::patch('/', [Admin\Settings\IndexController::class, 'update']);
    Route::patch('/mail', [Admin\Settings\MailController::class, 'update']);
    Route::patch('/advanced', [Admin\Settings\AdvancedController::class, 'update']);
});

/*
|--------------------------------------------------------------------------
| User Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/users
|
*/
Route::group(['prefix' => 'users'], function () {
    Route::get('/', [Admin\UserController::class, 'index'])->name('admin.users');
    Route::get('/accounts.json', [Admin\UserController::class, 'json'])->name('admin.users.json');
    Route::get('/new', [Admin\UserController::class, 'create'])->name('admin.users.new');
    Route::get('/view/{user:id}', [Admin\UserController::class, 'view'])->name('admin.users.view');

    Route::post('/new', [Admin\UserController::class, 'store']);

    Route::patch('/view/{user:id}', [Admin\UserController::class, 'update']);
    Route::delete('/view/{user:id}', [Admin\UserController::class, 'delete']);
});

/*
|--------------------------------------------------------------------------
| Server Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/servers
|
*/
Route::group(['prefix' => 'servers'], function () {
    Route::get('/', [Admin\Servers\ServerController::class, 'index'])->name('admin.servers');
    Route::get('/new', [Admin\Servers\CreateServerController::class, 'index'])->name('admin.servers.new');
    Route::get('/view/{server:id}', [Admin\Servers\ServerViewController::class, 'index'])->name('admin.servers.view');

    Route::group(['middleware' => [ServerInstalled::class]], function () {
        Route::get('/view/{server:id}/details', [Admin\Servers\ServerViewController::class, 'details'])->name('admin.servers.view.details');
        Route::get('/view/{server:id}/build', [Admin\Servers\ServerViewController::class, 'build'])->name('admin.servers.view.build');
        Route::get('/view/{server:id}/startup', [Admin\Servers\ServerViewController::class, 'startup'])->name('admin.servers.view.startup');
        Route::get('/view/{server:id}/database', [Admin\Servers\ServerViewController::class, 'database'])->name('admin.servers.view.database');
        Route::get('/view/{server:id}/mounts', [Admin\Servers\ServerViewController::class, 'mounts'])->name('admin.servers.view.mounts');
    });

    Route::get('/view/{server:id}/manage', [Admin\Servers\ServerViewController::class, 'manage'])->name('admin.servers.view.manage');
    Route::get('/view/{server:id}/delete', [Admin\Servers\ServerViewController::class, 'delete'])->name('admin.servers.view.delete');

    Route::post('/new', [Admin\Servers\CreateServerController::class, 'store']);
    Route::post('/view/{server:id}/build', [Admin\ServersController::class, 'updateBuild']);
    Route::post('/view/{server:id}/startup', [Admin\ServersController::class, 'saveStartup']);
    Route::post('/view/{server:id}/database', [Admin\ServersController::class, 'newDatabase']);
    Route::post('/view/{server:id}/mounts', [Admin\ServersController::class, 'addMount'])->name('admin.servers.view.mounts.store');
    Route::post('/view/{server:id}/manage/toggle', [Admin\ServersController::class, 'toggleInstall'])->name('admin.servers.view.manage.toggle');
    Route::post('/view/{server:id}/manage/suspension', [Admin\ServersController::class, 'manageSuspension'])->name('admin.servers.view.manage.suspension');
    Route::post('/view/{server:id}/manage/reinstall', [Admin\ServersController::class, 'reinstallServer'])->name('admin.servers.view.manage.reinstall');
    Route::post('/view/{server:id}/manage/transfer', [Admin\Servers\ServerTransferController::class, 'transfer'])->name('admin.servers.view.manage.transfer');
    Route::post('/view/{server:id}/delete', [Admin\ServersController::class, 'delete']);

    Route::patch('/view/{server:id}/details', [Admin\ServersController::class, 'setDetails']);
    Route::patch('/view/{server:id}/database', [Admin\ServersController::class, 'resetDatabasePassword']);

    Route::delete('/view/{server:id}/database/{database:id}/delete', [Admin\ServersController::class, 'deleteDatabase'])->name('admin.servers.view.database.delete');
    Route::delete('/view/{server:id}/mounts/{mount:id}', [Admin\ServersController::class, 'deleteMount'])
        ->name('admin.servers.view.mounts.delete');
});

/*
|--------------------------------------------------------------------------
| Node Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/nodes
|
*/
Route::group(['prefix' => 'nodes'], function () {
    Route::get('/', [Admin\Nodes\NodeController::class, 'index'])->name('admin.nodes');
    Route::get('/new', [Admin\NodesController::class, 'create'])->name('admin.nodes.new');
    Route::get('/view/{node:id}', [Admin\Nodes\NodeViewController::class, 'index'])->name('admin.nodes.view');
    Route::get('/view/{node:id}/settings', [Admin\Nodes\NodeViewController::class, 'settings'])->name('admin.nodes.view.settings');
    Route::get('/view/{node:id}/configuration', [Admin\Nodes\NodeViewController::class, 'configuration'])->name('admin.nodes.view.configuration');
    Route::get('/view/{node:id}/allocation', [Admin\Nodes\NodeViewController::class, 'allocations'])->name('admin.nodes.view.allocation');
    Route::get('/view/{node:id}/servers', [Admin\Nodes\NodeViewController::class, 'servers'])->name('admin.nodes.view.servers');
    Route::get('/view/{node:id}/system-information', Admin\Nodes\SystemInformationController::class);

    Route::post('/new', [Admin\NodesController::class, 'store']);
    Route::post('/view/{node:id}/allocation', [Admin\NodesController::class, 'createAllocation']);
    Route::post('/view/{node:id}/allocation/remove', [Admin\NodesController::class, 'allocationRemoveBlock'])->name('admin.nodes.view.allocation.removeBlock');
    Route::post('/view/{node:id}/allocation/alias', [Admin\NodesController::class, 'allocationSetAlias'])->name('admin.nodes.view.allocation.setAlias');
    Route::post('/view/{node:id}/settings/token', Admin\NodeAutoDeployController::class)->name('admin.nodes.view.configuration.token');
    Route::get('/view/{node:id}/downtime', [Admin\Nodes\NodeDowntimeController::class, 'index'])->name('admin.nodes.view.downtime');
    Route::post('/view/{node:id}/downtime/update', [Admin\Nodes\NodeDowntimeController::class, 'update'])->name('admin.nodes.view.downtime.update');

    Route::patch('/view/{node:id}/settings', [Admin\NodesController::class, 'updateSettings']);

    Route::delete('/view/{node:id}/delete', [Admin\NodesController::class, 'delete'])->name('admin.nodes.view.delete');
    Route::delete('/view/{node:id}/allocation/remove/{allocation:id}', [Admin\NodesController::class, 'allocationRemoveSingle'])->name('admin.nodes.view.allocation.removeSingle');
    Route::delete('/view/{node:id}/allocations', [Admin\NodesController::class, 'allocationRemoveMultiple'])->name('admin.nodes.view.allocation.removeMultiple');
});

/*
|--------------------------------------------------------------------------
| Mount Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/mounts
|
*/
Route::group(['prefix' => 'mounts'], function () {
    Route::get('/', [Admin\MountController::class, 'index'])->name('admin.mounts');
    Route::get('/view/{mount:id}', [Admin\MountController::class, 'view'])->name('admin.mounts.view');

    Route::post('/', [Admin\MountController::class, 'create']);
    Route::post('/{mount:id}/eggs', [Admin\MountController::class, 'addEggs'])->name('admin.mounts.eggs');
    Route::post('/{mount:id}/nodes', [Admin\MountController::class, 'addNodes'])->name('admin.mounts.nodes');

    Route::patch('/view/{mount:id}', [Admin\MountController::class, 'update']);

    Route::delete('/{mount:id}/eggs/{egg_id}', [Admin\MountController::class, 'deleteEgg']);
    Route::delete('/{mount:id}/nodes/{node_id}', [Admin\MountController::class, 'deleteNode']);
});

/*
|--------------------------------------------------------------------------
| Nest Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/nests
|
*/
Route::group(['prefix' => 'nests'], function () {
    Route::get('/', [Admin\Nests\NestController::class, 'index'])->name('admin.nests');
    Route::get('/new', [Admin\Nests\NestController::class, 'create'])->name('admin.nests.new');
    Route::get('/view/{nest:id}', [Admin\Nests\NestController::class, 'view'])->name('admin.nests.view');
    Route::get('/egg/new', [Admin\Nests\EggController::class, 'create'])->name('admin.nests.egg.new');
    Route::get('/egg/{egg:id}', [Admin\Nests\EggController::class, 'view'])->name('admin.nests.egg.view');
    Route::get('/egg/{egg:id}/export', [Admin\Nests\EggShareController::class, 'export'])->name('admin.nests.egg.export');
    Route::get('/egg/{egg:id}/variables', [Admin\Nests\EggVariableController::class, 'view'])->name('admin.nests.egg.variables');
    Route::get('/egg/{egg:id}/scripts', [Admin\Nests\EggScriptController::class, 'index'])->name('admin.nests.egg.scripts');

    Route::post('/new', [Admin\Nests\NestController::class, 'store']);
    Route::post('/import', [Admin\Nests\EggShareController::class, 'import'])->name('admin.nests.egg.import');
    Route::post('/egg/new', [Admin\Nests\EggController::class, 'store']);
    Route::post('/egg/{egg:id}/variables', [Admin\Nests\EggVariableController::class, 'store']);

    Route::put('/egg/{egg:id}', [Admin\Nests\EggShareController::class, 'update']);

    Route::patch('/view/{nest:id}', [Admin\Nests\NestController::class, 'update']);
    Route::patch('/egg/{egg:id}', [Admin\Nests\EggController::class, 'update']);
    Route::patch('/egg/{egg:id}/scripts', [Admin\Nests\EggScriptController::class, 'update']);
    Route::patch('/egg/{egg:id}/variables/{variable:id}', [Admin\Nests\EggVariableController::class, 'update'])->name('admin.nests.egg.variables.edit');

    Route::delete('/view/{nest:id}', [Admin\Nests\NestController::class, 'destroy']);
    Route::delete('/egg/{egg:id}', [Admin\Nests\EggController::class, 'destroy']);
    Route::delete('/egg/{egg:id}/variables/{variable:id}', [Admin\Nests\EggVariableController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Elysium Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/elysium
|
*/
Route::group(['prefix' => 'elysium'], function () {
    Route::get('/', [Admin\Elysium\GeneralController::class, 'index'])->name('admin.elysium');
    Route::post('/update', [Admin\Elysium\GeneralController::class, 'update'])->name('admin.elysium.update');

    Route::get('/meta', [Admin\Elysium\MetaController::class, 'index'])->name('admin.elysium.meta');
    Route::post('/meta/update', [Admin\Elysium\MetaController::class, 'update'])->name('admin.elysium.meta.update');

    Route::get('/color', [Admin\Elysium\ColorController::class, 'index'])->name('admin.elysium.color');
    Route::post('/color/update', [Admin\Elysium\ColorController::class, 'update'])->name('admin.elysium.color.update');

    Route::get('/announcement', [Admin\Elysium\AnnouncementController::class, 'index'])->name('admin.elysium.announcement');
    Route::post('/announcement/update', [Admin\Elysium\AnnouncementController::class, 'update'])->name('admin.elysium.announcement.update');
});

/*
|--------------------------------------------------------------------------
| Auto Allocation Adder Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/autoallocation
|
*/
Route::group(['prefix' => 'autoallocation'], function () {
    Route::get('/', [Admin\AutoAllocationController::class, 'index'])->name('admin.autoallocation');
    Route::get('/new', [Admin\AutoAllocationController::class, 'new'])->name('admin.autoallocation.new');
    Route::get('/edit/{id}', [Admin\AutoAllocationController::class, 'edit'])->name('admin.autoallocation.edit');

    Route::post('/create', [Admin\AutoAllocationController::class, 'create'])->name('admin.autoallocation.create');
    Route::post('/update/{id}', [Admin\AutoAllocationController::class, 'update'])->name('admin.autoallocation.update');
	Route::post('/apply', [Admin\AutoAllocationController::class, 'apply'])->name('admin.autoallocation.apply');

    Route::delete('/delete', [Admin\AutoAllocationController::class, 'delete'])->name('admin.autoallocation.delete');
});


/*
|--------------------------------------------------------------------------
| Permission Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/permissions
|
*/
Route::group(['prefix' => 'permissions'], function () {
    Route::get('/', [Admin\PermissionController::class, 'index'])->name('admin.permissions.index');
    Route::get('/new', [Admin\PermissionController::class, 'new'])->name('admin.permissions.new');
    Route::get('/edit/{role:id}', [Admin\PermissionController::class, 'edit'])->name('admin.permissions.edit');

    Route::get('/delete/{role:id}', [Admin\PermissionController::class, 'destroy']);

    Route::post('/new', [Admin\PermissionController::class, 'create']);
    Route::post('/edit/{role:id}', [Admin\PermissionController::class, 'update']);
});

/*
|--------------------------------------------------------------------------
| Player Counter Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/players
|
*/
Route::group(['prefix' => 'players'], function () {
    Route::get('/', [Admin\PlayerCounterController::class, 'index'])->name('admin.players');

    Route::post('/create', [Admin\PlayerCounterController::class, 'create'])->name('admin.players.create');
    Route::post('/update', [Admin\PlayerCounterController::class, 'update'])->name('admin.players.update');

    Route::delete('/delete', [Admin\PlayerCounterController::class, 'delete'])->name('admin.players.delete');
});

/*
|--------------------------------------------------------------------------
| Velta Studios Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/veltastudios
|
*/
Route::group(['prefix' => 'veltastudios'], function () {
    Route::get('/schedule-templates', [Admin\VeltaStudios\ScheduleTemplateController::class, 'index'])->name('admin.veltastudios.schedule-templates');
    Route::get('/schedule-templates/version', [Admin\VeltaStudios\ScheduleTemplateController::class, 'getVersion'])->name('admin.veltastudios.schedule-templates.version');
    Route::get('/schedule-templates/create', [Admin\VeltaStudios\ScheduleTemplateController::class, 'create'])->name('admin.veltastudios.schedule-templates.create');
    Route::post('/schedule-templates', [Admin\VeltaStudios\ScheduleTemplateController::class, 'store'])->name('admin.veltastudios.schedule-templates.store');
    Route::get('/schedule-templates/{id}/edit', [Admin\VeltaStudios\ScheduleTemplateController::class, 'edit'])->name('admin.veltastudios.schedule-templates.edit');
    Route::patch('/schedule-templates/{id}', [Admin\VeltaStudios\ScheduleTemplateController::class, 'update'])->name('admin.veltastudios.schedule-templates.update');
    Route::delete('/schedule-templates/{id}', [Admin\VeltaStudios\ScheduleTemplateController::class, 'destroy'])->name('admin.veltastudios.schedule-templates.destroy');
});

/*
|--------------------------------------------------------------------------
| Automatic phpMyAdmin Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/automatic-phpmyadmin/
|
*/
Route::group(['prefix' => 'automatic-phpmyadmin'], function () {
   Route::get('/', [Admin\AktiCubeDevelopmentTeam\AutomaticPhpMyAdminController::class, 'index'])->name('admin.akticube.automatic-phpmyadmin');
   Route::get('/new', [Admin\AktiCubeDevelopmentTeam\AutomaticPhpMyAdminController::class, 'create'])->name('admin.akticube.automatic-phpmyadmin.new');
   Route::get('/view/{automaticphpmyadmin:id}', [Admin\AktiCubeDevelopmentTeam\AutomaticPhpMyAdminController::class, 'view'])->name('admin.akticube.automatic-phpmyadmin.view');

   Route::post('/new', [Admin\AktiCubeDevelopmentTeam\AutomaticPhpMyAdminController::class, 'store']);

   Route::patch('/view/{automaticphpmyadmin:id}', [Admin\AktiCubeDevelopmentTeam\AutomaticPhpMyAdminController::class, 'update']);

   Route::delete('/delete/{automaticphpmyadmin:id}', [Admin\AktiCubeDevelopmentTeam\AutomaticPhpMyAdminController::class, 'destroy'])->name('admin.akticube.automatic-phpmyadmin.delete');
});

/*
|--------------------------------------------------------------------------
| Updater Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/updater
| Copyright (c) 2024 it's vic! All Rights Reserved.
*/
Route::group(['prefix' => 'updater'], function () {
    Route::get('/', [Admin\UpdaterController::class, 'index'])->name('admin.updater');
    Route::get('/log', [Admin\UpdaterController::class, 'log'])->name('admin.updater.log');

    Route::post('/', [Admin\UpdaterController::class, 'update']);
    Route::post('/restore', [Admin\UpdaterController::class, 'restore'])->name('admin.updater.restore');
});