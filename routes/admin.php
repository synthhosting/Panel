<?php

use Illuminate\Support\Facades\Route;
use Pterodactyl\Http\Controllers\Admin;
use Pterodactyl\Http\Middleware\Admin\Servers\ServerInstalled;

Route::get('/', [Admin\BaseController::class, 'index'])->name('admin.index');

/*
|--------------------------------------------------------------------------
| Knowledgebase Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/knowledgebase
|
*/
Route::group(['prefix' => 'knowledgebase'], function () {
    Route::get('/', [Admin\KnowledgebaseController::class, 'index'])->name('admin.knowledgebase.index');

    Route::group(['prefix' => 'topics'], function () {
        Route::get('/new', [Admin\KnowledgebaseController::class, 'newtopic'])->name('admin.knowledgebase.topics.new');
        Route::get('/edit/{id}', [Admin\KnowledgebaseController::class, 'edittopic'])->name('admin.knowledgebase.topics.edit');
        Route::post('/delete/{id}', [Admin\KnowledgebaseController::class, 'deletetopic'])->name('admin.knowledgebase.topics.delete');
        Route::post('/create', [Admin\KnowledgebaseController::class, 'createtopic'])->name('admin.knowledgebase.topics.create');
        Route::post('/update/{id}', [Admin\KnowledgebaseController::class, 'updatetopic'])->name('admin.knowledgebase.topics.update');
    });

    Route::group(['prefix' => 'category'], function () {
        Route::get('/new', [Admin\KnowledgebaseController::class, 'newcategory'])->name('admin.knowledgebase.category.new');
        Route::get('/edit/{id}', [Admin\KnowledgebaseController::class, 'editcategory'])->name('admin.knowledgebase.category.edit');
        Route::post('/delete/{id}', [Admin\KnowledgebaseController::class, 'deletecategory'])->name('admin.knowledgebase.category.delete');
        Route::post('/create', [Admin\KnowledgebaseController::class, 'createcategory'])->name('admin.knowledgebase.category.create');
        Route::post('/update/{id}', [Admin\KnowledgebaseController::class, 'updatecategory'])->name('admin.knowledgebase.category.update');
    });
});

/*
|--------------------------------------------------------------------------
| Helionix Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/helionix
|
*/
Route::group(['prefix' => 'helionix'], function () {
    Route::get('/', [Admin\Helionix\GeneralController::class, 'index'])->name('admin.helionix.general');
    Route::post('/', [Admin\Helionix\GeneralController::class, 'store']);

    Route::get('/meta', [Admin\Helionix\MetaController::class, 'index'])->name('admin.helionix.meta');
    Route::post('/meta', [Admin\Helionix\MetaController::class, 'store']);

    Route::get('/color', [Admin\Helionix\ColorController::class, 'index'])->name('admin.helionix.color');
    Route::post('/color', [Admin\Helionix\ColorController::class, 'store']);

    Route::get('/alert', [Admin\Helionix\AlertController::class, 'index'])->name('admin.helionix.alert');
    Route::post('/alert', [Admin\Helionix\AlertController::class, 'store']);

    Route::get('/announcement', [Admin\Helionix\AnnouncementController::class, 'index'])->name('admin.helionix.announcement');
    Route::post('/announcement', [Admin\Helionix\AnnouncementController::class, 'store']);
    Route::get('/announcement/new', [Admin\Helionix\AnnouncementController::class, 'create'])->name('admin.helionix.announcement.create');
    Route::post('/announcement/new', [Admin\Helionix\AnnouncementController::class, 'new']);
    Route::get('/announcement/edit/{id}', [Admin\Helionix\AnnouncementController::class, 'edit'])->name('admin.helionix.announcement.edit');
    Route::post('/announcement/edit/{id}', [Admin\Helionix\AnnouncementController::class, 'update']);
    Route::delete('/announcement/delete/{id}', [Admin\Helionix\AnnouncementController::class, 'delete'])->name('admin.helionix.announcement.delete');

    Route::get('/dashboard', [Admin\Helionix\DashboardController::class, 'index'])->name('admin.helionix.dashboard');
    Route::post('/dashboard', [Admin\Helionix\DashboardController::class, 'store']);

    Route::get('/uptime', [Admin\Helionix\UptimeController::class, 'index'])->name('admin.helionix.uptime');
    Route::post('/uptime', [Admin\Helionix\UptimeController::class, 'store']);

    Route::get('/server', [Admin\Helionix\ServerController::class, 'index'])->name('admin.helionix.server');
    Route::post('/server', [Admin\Helionix\ServerController::class, 'store']);

    Route::get('/authentication', [Admin\Helionix\AuthenticationController::class, 'index'])->name('admin.helionix.authentication');
    Route::post('/authentication', [Admin\Helionix\AuthenticationController::class, 'store']);
});

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
    Route::get('/loginasuser/{user:id}', [Admin\LoginAsUserController::class, 'loginasuser'])->name('admin.users.loginasuser');

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
| Discord Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/discord
|
*/
Route::group(['prefix' => '/discord'], function () {
    Route::get('/', [Admin\DiscordController::class, 'index'])->name('admin.discord');

    Route::post('/save', [Admin\DiscordController::class, 'save'])->name('admin.discord.save');
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
| Node Backup Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/node-backup/
|
*/
Route::group(['prefix' => 'node-backup'], function() {
    Route::get('/', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'index'])->name('admin.akticube.node-backup');
    Route::get('/statistics', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'statistics'])->name('admin.akticube.node-backup.statistics');
    Route::group(['prefix' => 'group'], function() {
        Route::get('/new', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'createNodeBackupGroup'])->name('admin.akticube.node-backup.group.new');
        Route::post('/new', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'storeNodeBackupGroup'])->name('admin.akticube.node-backup.group.store');
        Route::group(['prefix' => '{nodeBackupGroupId}'], function() {
            Route::get('/', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'viewNodeBackupGroup'])->name('admin.akticube.node-backup.group.view');
            Route::get('/edit', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'editNodeBackupGroup'])->name('admin.akticube.node-backup.group.edit');
            Route::patch('/edit', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'updateNodeBackupGroup'])->name('admin.akticube.node-backup.group.update');
            Route::delete('/delete', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'destroyNodeBackupGroup'])->name('admin.akticube.node-backup.group.delete');
            Route::group(['prefix' => 'backup'], function() {
                Route::get('/new', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'createNodeBackup'])->name('admin.akticube.node-backup.group.backup.new');
                Route::post('/new', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'storeNodeBackup'])->name('admin.akticube.node-backup.group.backup.store');
                Route::group(['prefix' => '{nodeBackupId}'], function() {
                    Route::get('/', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'viewNodeBackup'])->name('admin.akticube.node-backup.group.backup.view');
                    Route::get('/restore', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'restoreNodeBackup'])->name('admin.akticube.node-backup.group.backup.restore');
                    Route::get('/restore-on-another-node/{nodeId}', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'restoreNodeBackupOnAnotherNode'])->name('admin.akticube.node-backup.group.backup.restore-on-another-node');
                    Route::get('/stop', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'stopNodeBackup'])->name('admin.akticube.node-backup.group.backup.stop');
                    Route::get('/try-again', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'tryAgainNodeBackup'])->name('admin.akticube.node-backup.group.backup.try-again');
                    Route::delete('/delete', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'destroyNodeBackup'])->name('admin.akticube.node-backup.group.backup.delete');
                    Route::group(['prefix' => '/server-backup/{nodeBackupServerId}'], function() {
                        Route::get('/try-again', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'tryAgainNodeBackupServer'])->name('admin.akticube.node-backup.group.backup.server.try-again');
                        Route::get('/download', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'downloadNodeBackupServer'])->name('admin.akticube.node-backup.group.backup.server.download');
                        Route::get('/restore', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'restoreNodeBackupServer'])->name('admin.akticube.node-backup.group.backup.server.restore');
                        Route::get('/restore-on-another-node/{nodeId}', [Admin\AktiCubeDevelopmentTeam\NodeBackupController::class, 'restoreNodeBackupServerOnAnotherNode'])->name('admin.akticube.node-backup.group.backup.server.restore-on-another-node');
                    });
                });
            });
        });
    });
    Route::group(['prefix' => 's3-server'], function () {
        Route::get('/', [Admin\AktiCubeDevelopmentTeam\NodeBackupS3ServerController::class, 'index'])->name('admin.akticube.node-backup.s3-server');
        Route::get('/new', [Admin\AktiCubeDevelopmentTeam\NodeBackupS3ServerController::class, 'create'])->name('admin.akticube.node-backup.s3-server.new');
        Route::post('/new', [Admin\AktiCubeDevelopmentTeam\NodeBackupS3ServerController::class, 'store'])->name('admin.akticube.node-backup.s3-server.store');
        Route::group(['prefix' => '{nodeBackupS3ServerId}'], function() {
            Route::get('/', [Admin\AktiCubeDevelopmentTeam\NodeBackupS3ServerController::class, 'view'])->name('admin.akticube.node-backup.s3-server.view');
            Route::patch('/', [Admin\AktiCubeDevelopmentTeam\NodeBackupS3ServerController::class, 'update'])->name('admin.akticube.node-backup.s3-server.update');
            Route::delete('/', [Admin\AktiCubeDevelopmentTeam\NodeBackupS3ServerController::class, 'destroy'])->name('admin.akticube.node-backup.s3-server.delete');
        });
    });
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
    Route::get('/nodes/wings-updater', [Admin\VeltaStudios\WingsUpdaterController::class, 'index'])->name('admin.veltastudios.nodes.wings-updater.index');
    Route::get('/nodes/wings-updater/version', [Admin\VeltaStudios\WingsUpdaterController::class, 'getVersion'])->name('admin.veltastudios.nodes.wings-updater.version');
    Route::get('/nodes/wings-updater/wings-version', [Admin\VeltaStudios\WingsUpdaterController::class, 'getWingsVersion'])->name('admin.veltastudios.nodes.wings-updater.wingsVersion');
    Route::get('/nodes/wings-updater/update', [Admin\VeltaStudios\WingsUpdaterController::class, 'showUpdatePage'])->name('admin.veltastudios.nodes.wings-updater.showUpdatePage');
    Route::post('/nodes/wings-updater/update', [Admin\VeltaStudios\WingsUpdaterController::class, 'executeUpdate'])->name('admin.veltastudios.nodes.wings-updater.executeUpdate');
    Route::post('/nodes/wings-updater/configure', [Admin\VeltaStudios\WingsUpdaterController::class, 'saveConfiguration'])->name('admin.veltastudios.nodes.wings-updater.saveConfiguration');
    Route::get('/nodes/wings-updater/test', [Admin\VeltaStudios\WingsUpdaterController::class, 'testConnections'])->name('admin.veltastudios.nodes.wings-updater.testConnections');
    Route::get('/nodes/wings-updater/test/{nodeId}', [Admin\VeltaStudios\WingsUpdaterController::class, 'testConnection'])->name('admin.veltastudios.nodes.wings-updater.testConnection');
    Route::post('/nodes/wings-updater/update/{nodeId}', [Admin\VeltaStudios\WingsUpdaterController::class, 'updateNode'])->name('admin.veltastudios.nodes.wings-updater.update');
    Route::get('/nodes/system-information/{nodeId}', [Admin\VeltaStudios\WingsUpdaterController::class, 'getNodeSystemInformation'])->name('admin.veltastudios.nodes.systemInformation');
});