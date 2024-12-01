<?php

use Illuminate\Support\Facades\Route;
use Pterodactyl\Http\Controllers\Api\Client;
use Pterodactyl\Http\Middleware\Activity\ServerSubject;
use Pterodactyl\Http\Middleware\Activity\AccountSubject;
use Pterodactyl\Http\Middleware\RequireTwoFactorAuthentication;
use Pterodactyl\Http\Middleware\Api\Client\Server\ResourceBelongsToServer;
use Pterodactyl\Http\Middleware\Api\Client\Server\AuthenticateServerAccess;
use Pterodactyl\Http\Middleware\CheckFileAccess;

/*
|--------------------------------------------------------------------------
| Client Control API
|--------------------------------------------------------------------------
|
| Endpoint: /api/client
|
*/
Route::get('/', [Client\ClientController::class, 'index'])->name('api:client.index');
Route::get('/role', [Client\GetUserRoleController::class, 'index']);
Route::get('/permissions', [Client\ClientController::class, 'permissions']);

Route::group(['prefix' => '/knowledgebase'], function () {
    Route::get('/categories', [Client\KnowledgebaseController::class, 'categories'])->name('api:client.knowledgebase.categories');
    Route::get('/topics', [Client\KnowledgebaseController::class, 'topics'])->name('api:client.knowledgebase.topics');
    Route::get('/topics-from/{id}', [Client\KnowledgebaseController::class, 'topicsfrom'])->name('api:client.knowledgebase.topics');
    Route::get('/topic/{id}', [Client\KnowledgebaseController::class, 'topic'])->name('api:client.knowledgebase.topics');
});

Route::prefix('/account')->middleware(AccountSubject::class)->group(function () {
    Route::prefix('/')->withoutMiddleware(RequireTwoFactorAuthentication::class)->group(function () {
        Route::get('/', [Client\AccountController::class, 'index'])->name('api:client.account');
        Route::get('/two-factor', [Client\TwoFactorController::class, 'index']);
        Route::post('/two-factor', [Client\TwoFactorController::class, 'store']);
        Route::delete('/two-factor', [Client\TwoFactorController::class, 'delete']);
    });

    Route::put('/email', [Client\AccountController::class, 'updateEmail'])->name('api:client.account.update-email');
    Route::put('/password', [Client\AccountController::class, 'updatePassword'])->name('api:client.account.update-password');

    Route::get('/activity', Client\ActivityLogController::class)->name('api:client.account.activity');

    Route::get('/api-keys', [Client\ApiKeyController::class, 'index']);
    Route::post('/api-keys', [Client\ApiKeyController::class, 'store']);
    Route::delete('/api-keys/{identifier}', [Client\ApiKeyController::class, 'delete']);

    Route::group(['prefix' => '/discord'], function () {
        Route::get('/', [Client\DiscordConnectController::class, 'index']);
    
        Route::post('/auth', [Client\DiscordConnectController::class, 'generateAuthURL']);
        Route::post('/verify', [Client\DiscordConnectController::class, 'validateAuth']);
    });

    Route::prefix('/ssh-keys')->group(function () {
        Route::get('/', [Client\SSHKeyController::class, 'index']);
        Route::post('/', [Client\SSHKeyController::class, 'store']);
        Route::post('/remove', [Client\SSHKeyController::class, 'delete']);
    });
});

/*
|--------------------------------------------------------------------------
| Client Control API
|--------------------------------------------------------------------------
|
| Endpoint: /api/client/servers/{server}
|
*/
Route::group([
    'prefix' => '/servers/{server}',
    'middleware' => [
        ServerSubject::class,
        AuthenticateServerAccess::class,
        ResourceBelongsToServer::class,
    ],
], function () {
    Route::get('/', [Client\Servers\ServerController::class, 'index'])->name('api:client:server.view');
    Route::get('/websocket', Client\Servers\WebsocketController::class)->name('api:client:server.ws');
    Route::get('/resources', Client\Servers\ResourceUtilizationController::class)->name('api:client:server.resources');
    Route::get('/activity', Client\Servers\ActivityLogController::class)->name('api:client:server.activity');

    Route::post('/command', [Client\Servers\CommandController::class, 'index']);
    Route::post('/power', [Client\Servers\PowerController::class, 'index']);
    Route::get('/downtime', [Client\Servers\DowntimeController::class, 'index'])->name('api:client:server.downtime');

    Route::group(['prefix' => '/databases'], function () {
        Route::get('/', [Client\Servers\DatabaseController::class, 'index']);
        Route::post('/', [Client\Servers\DatabaseController::class, 'store']);
        Route::post('/{database}/rotate-password', [Client\Servers\DatabaseController::class, 'rotatePassword']);
        Route::delete('/{database}', [Client\Servers\DatabaseController::class, 'delete']);
        Route::post('/{database}/getToken', [Client\Servers\DatabaseController::class, 'getToken']);
    });

    Route::group(['prefix' => '/files',
        'middleware' => [
            CheckFileAccess::class,
        ],
    ], function () {
        Route::get('/list', [Client\Servers\FileController::class, 'directory'])->withoutMiddleware(CheckFileAccess::class);
        Route::get('/contents', [Client\Servers\FileController::class, 'contents']);
        Route::get('/download', [Client\Servers\FileController::class, 'download']);
        Route::put('/rename', [Client\Servers\FileController::class, 'rename']);
        Route::post('/copy', [Client\Servers\FileController::class, 'copy']);
        Route::post('/write', [Client\Servers\FileController::class, 'write']);
        Route::post('/compress', [Client\Servers\FileController::class, 'compress']);
        Route::post('/decompress', [Client\Servers\FileController::class, 'decompress']);
        Route::post('/delete', [Client\Servers\FileController::class, 'delete']);
        Route::post('/create-folder', [Client\Servers\FileController::class, 'create']);
        Route::post('/chmod', [Client\Servers\FileController::class, 'chmod']);
        Route::post('/size', [Client\Servers\FolderSizeController::class, 'getFolderSize']);
        Route::post('/pull', [Client\Servers\FileController::class, 'pull'])->middleware(['throttle:10,5']);
        Route::get('/upload', Client\Servers\FileUploadController::class);
        Route::post('/search/smart', [Client\Servers\SmartSearchController::class, 'search'])->middleware(['throttle:3,1']);
    });

    Route::group(['prefix' => '/schedules'], function () {
        Route::get('/', [Client\Servers\ScheduleController::class, 'index']);
        Route::post('/', [Client\Servers\ScheduleController::class, 'store']);
        Route::get('/templates', [Client\Servers\ScheduleTemplateController::class, 'index']);
        Route::get('/{schedule}', [Client\Servers\ScheduleController::class, 'view']);
        Route::post('/{schedule}', [Client\Servers\ScheduleController::class, 'update']);
        Route::post('/{schedule}/execute', [Client\Servers\ScheduleController::class, 'execute']);
        Route::delete('/{schedule}', [Client\Servers\ScheduleController::class, 'delete']);

        Route::post('/{schedule}/tasks', [Client\Servers\ScheduleTaskController::class, 'store']);
        Route::post('/{schedule}/tasks/{task}', [Client\Servers\ScheduleTaskController::class, 'update']);
        Route::delete('/{schedule}/tasks/{task}', [Client\Servers\ScheduleTaskController::class, 'delete']);
    });

    Route::group(['prefix' => '/network'], function () {
        Route::get('/allocations', [Client\Servers\NetworkAllocationController::class, 'index']);
        Route::post('/allocations', [Client\Servers\NetworkAllocationController::class, 'store']);
        Route::post('/allocations/{allocation}', [Client\Servers\NetworkAllocationController::class, 'update']);
        Route::post('/allocations/{allocation}/primary', [Client\Servers\NetworkAllocationController::class, 'setPrimary']);
        Route::delete('/allocations/{allocation}', [Client\Servers\NetworkAllocationController::class, 'delete']);
    });

    Route::group(['prefix' => '/users'], function () {
        Route::get('/', [Client\Servers\SubuserController::class, 'index']);
        Route::post('/', [Client\Servers\SubuserController::class, 'store']);
        Route::get('/{user}', [Client\Servers\SubuserController::class, 'view']);
        Route::post('/{user}', [Client\Servers\SubuserController::class, 'update']);
        Route::post('/{user}/files', [Client\Servers\SubuserController::class, 'editdeny']);
        Route::delete('/{user}', [Client\Servers\SubuserController::class, 'delete']);
    });

    Route::group(['prefix' => '/backups'], function () {
        Route::get('/', [Client\Servers\BackupController::class, 'index']);
        Route::post('/', [Client\Servers\BackupController::class, 'store']);
        Route::get('/{backup}', [Client\Servers\BackupController::class, 'view']);
        Route::get('/{backup}/download', [Client\Servers\BackupController::class, 'download']);
        Route::post('/{backup}/lock', [Client\Servers\BackupController::class, 'toggleLock']);
        Route::post('/{backup}/restore', [Client\Servers\BackupController::class, 'restore']);
        Route::delete('/{backup}', [Client\Servers\BackupController::class, 'delete']);
    });

    Route::group(['prefix' => '/startup'], function () {
        Route::get('/', [Client\Servers\StartupController::class, 'index']);
        Route::put('/variable', [Client\Servers\StartupController::class, 'update']);
    });

    Route::get('/players', [Client\Servers\PlayersController::class, 'index']);

    Route::group(['prefix' => '/settings'], function () {
        Route::post('/rename', [Client\Servers\SettingsController::class, 'rename']);
        Route::post('/reinstall', [Client\Servers\SettingsController::class, 'reinstall']);
        Route::put('/docker-image', [Client\Servers\SettingsController::class, 'dockerImage']);
        Route::post('/timezone', [Client\Servers\SettingsController::class, 'timezone']);
    });

    Route::group(['prefix' => '/rustplugins'], function () {
        Route::post('/', [Client\Servers\RustPluginsController::class, 'index']);
        Route::post('/install', [Client\Servers\RustPluginsController::class, 'store']);
    });

    Route::group(['prefix' => '/wipe'], function () {
        Route::get('/', [Client\Servers\WipeController::class, 'index']);
    
        Route::post('/timezone', [Client\Servers\WipeController::class, 'timezone']);
        Route::post('/map', [Client\Servers\WipeController::class, 'map']);
        Route::post('/{wipe:id?}', [Client\Servers\WipeController::class, 'store']);
    
        Route::delete('/map/{wipemap:id}', [Client\Servers\WipeController::class, 'deleteMap']);
        Route::delete('/{wipe:id}', [Client\Servers\WipeController::class, 'delete']);
    });

    Route::group(['prefix' => '/modpacks'], function () {
        Route::get('/', [Client\Servers\ModpackController::class, 'index']);
        Route::get('/versions', [Client\Servers\ModpackController::class, 'versions']);
        Route::post('/install', [Client\Servers\ModpackController::class, 'install']);
    });


});

/*
|--------------------------------------------------------------------------
| Client Control API
|--------------------------------------------------------------------------
|
| Endpoint: /api/client/announcements
|
*/
Route::group(['prefix' => '/announcements'], function () {
    Route::get('/', [Client\Helionix\AnnouncementController::class, 'index']);
    Route::get('/{id}', [Client\Helionix\AnnouncementController::class, 'detail']);
});

/*
|--------------------------------------------------------------------------
| Client Control API
|--------------------------------------------------------------------------
|
| Endpoint: /api/client/uptimes
|
*/
Route::group(['prefix' => '/uptimes'], function () {
    Route::get('/', [Client\Helionix\UptimeController::class, 'index']);
});