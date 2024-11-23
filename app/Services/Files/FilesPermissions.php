<?php

namespace Pterodactyl\Services\Files;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Pterodactyl\Models\Egg;
use Pterodactyl\Models\Server;
use Pterodactyl\Models\User;
use Pterodactyl\Repositories\Eloquent\SettingsRepository;

class FilesPermissions
{
    private SettingsRepository $settingsRepository;

    public function __construct(
        SettingsRepository $settingsRepository,
    ) {
        $this->settingsRepository = $settingsRepository;
    }

    public string $ApiUrl = "https://addons.minerpl.xyz/sftperms/validate?includes=hiddenFiles";

    public function getAdminDeny($admin = false): array
    {
        if ($admin) {
            return [];
        }
        return [
            "files" => json_decode($this->settingsRepository->get('settings::denyfiles', "[]"), true),
            "hide" => $this->settingsRepository->get('settings::hidefiles', "false") === "true"
        ];
    }

    public function getEggDeny(Egg $egg): array
    {
        return [
            "files" => json_decode($egg->denyfiles, true),
            "hide" => $egg->hidefiles
        ];
    }

    public function getSubuserDeny($user, $server): array
    {
        $subuser = $server->subusers()->where('user_id', $user->id)->first();
        if($subuser) {
            return [
                "files" => $subuser->denyfiles,
                "hide" => $subuser->hidefiles,
            ];
        } else {
            return [
                "files" => [],
                "hide" => "false",
            ];
        }
    }

    private function createRequest($url, $body)
    {
        try {
            return Http::asForm()
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Panel' => env("APP_URL"),
                    'Placeholders' => "BuiltByBit vinnij (468464) v167510 (1732330381)"
                ])
                ->post($url, $body)
                ->json();
        } catch (\Throwable|\Exception $exception) {
            Log::error($exception, [
                'url' => $url,
                'body' => $body,
            ]);

            if(in_array('file', $body)) {
                return [
                    "access" => true
                ];
            } else {
                return [
                    "acceptedFiles" => $body['files'],
                    "hiddenFiles" => []
                ];
            }
        }
    }

    public function getPermissionsObject(User $user, Server $server)
    {
        if ($user->root_admin) {
            return [
                "User" => [],
                "Admin" => [],
                "Egg" => [],
                "HideFiles" => [
                    "User" => "false",
                    "Admin" => "false",
                    "Egg" => "false"
                ]
            ];
        }

        $userDeny = $this->getSubuserDeny($user, $server);
        $adminDeny = $this->getAdminDeny($user->root_admin);
        $eggDeny = $this->getEggDeny($server->egg()->first());

        return [
            "User" => $userDeny["files"],
            "Admin" => $adminDeny["files"],
            "Egg" => $eggDeny["files"],
            "HideFiles" => [
                "User" => $userDeny["hide"] ? "true" : "false",
                "Admin" => $adminDeny["hide"] ? "true" : "false",
                "Egg" => $eggDeny["hide"] ? "true" : "false"
            ]
        ];
    }

    public function hasAccess($request, $server, $file = null)
    {
        if($request->user()->root_admin) {
            return true;
        }

        $denyFiles = $this->getPermissionsObject($request->user(), $server);

        if(!$file) $file = $request->get('file');

        return $this->createRequest(
            $this->ApiUrl,
            [
                'admin' => base64_encode(implode(",", $denyFiles['Admin'])),
                'deny' => base64_encode(implode(",", $denyFiles['User'])),
                'egg' => base64_encode(implode(",", $denyFiles['Egg'])),
                'file' => $file
            ]
        )['access'];
    }

    public function checkArrayAccess($request, $server, $content, $root): array
    {
        if($request->user()->root_admin) {
            return [
                "acceptedFiles" => $content,
                "hiddenFiles" => []
            ];
        }

        $denyFiles = $this->getPermissionsObject($request->user(), $server);
        $userHide = $denyFiles['HideFiles']['User'];
        $adminHide = $denyFiles['HideFiles']['Admin'];
        $eggHide = $denyFiles['HideFiles']['Egg'];

        return $this->createRequest(
            $this->ApiUrl,
            [
                'admin' => base64_encode(implode(",", $denyFiles['Admin'])),
                'deny' => base64_encode(implode(",", $denyFiles['User'])),
                'egg' => base64_encode(implode(",", $denyFiles['Egg'])),
                'root' => $root,
                'files' => base64_encode(implode(",", $content)),
                'hideFiles' => $userHide . "|" . $adminHide . "|" . $eggHide
            ]
        );
    }
}
