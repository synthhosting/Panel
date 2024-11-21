<?php

namespace Pterodactyl\Http\ViewComposers;

use Illuminate\View\View;
use Pterodactyl\Services\Helpers\AssetHashService;
use Pterodactyl\Contracts\Repository\SettingsRepositoryInterface;

class AssetComposer
{
    /**
     * AssetComposer constructor.
     */
    public function __construct(private AssetHashService $assetHashService, private SettingsRepositoryInterface $helionix)
    {
    }

    /**
     * Provide access to the asset service in the views.
     */
    public function compose(View $view): void
    {
        $view->with('asset', $this->assetHashService);
        $view->with('siteConfiguration', [
            'name' => config('app.name') ?? 'Pterodactyl',
            'locale' => config('app.locale') ?? 'en',
            'recaptcha' => [
                'enabled' => config('recaptcha.enabled', false),
                'siteKey' => config('recaptcha.website_key') ?? '',
            ],
        ]);
        $view->with('helionixConfiguration', [
            'logo' => $this->helionix->get('helionix::helionix:logo', '/favicons/android-chrome-512x512.png'),
            'favicon' => $this->helionix->get('helionix::helionix:favicon', '/favicons/android-chrome-512x512.png'),
            'logo_only' => $this->helionix->get('helionix::helionix:logo_only', false),
            'logo_height' => $this->helionix->get('helionix::helionix:logo_height', '48px'),
            
            'meta_logo' => $this->helionix->get('helionix::helionix:meta_logo', '/favicons/android-chrome-512x512.png'),
            'meta_title' => $this->helionix->get('helionix::helionix:meta_title', 'Helionix'),
            'meta_description' => $this->helionix->get('helionix::helionix:meta_description', 'Easily customize your Pterodactyl panel with the Helionix Theme'),
            'meta_color' => $this->helionix->get('helionix::helionix:meta_color', '#2DCE89'),

            'color_1' => $this->helionix->get('helionix::helionix:color_1', '#0F121A'),
            'color_2' => $this->helionix->get('helionix::helionix:color_2', '#151923'),
            'color_3' => $this->helionix->get('helionix::helionix:color_3', '#212633'),
            'color_4' => $this->helionix->get('helionix::helionix:color_4', '#2D3340'),
            'color_5' => $this->helionix->get('helionix::helionix:color_5', '#3A3f4D'),
            'color_6' => $this->helionix->get('helionix::helionix:color_6', '#474D5B'),
            'color_console' => $this->helionix->get('helionix::helionix:color_console', '#151923'),
            'color_editor' => $this->helionix->get('helionix::helionix:color_editor', '#151923'),
            'button_primary' => $this->helionix->get('helionix::helionix:button_primary', '#2DCE89'),
            'button_primary_hover' => $this->helionix->get('helionix::helionix:button_primary_hover', '#20AB6F'),
            'button_secondary' => $this->helionix->get('helionix::helionix:button_secondary', '#A9B0C1'),
            'button_secondary_hover' => $this->helionix->get('helionix::helionix:button_secondary_hover', '#9EA5B6'),
            'button_danger' => $this->helionix->get('helionix::helionix:button_danger', '#FF3A62'),
            'button_danger_hover' => $this->helionix->get('helionix::helionix:button_danger_hover', '#EE2A51'),
            'color_h1' => $this->helionix->get('helionix::helionix:color_h1', '#FFFFFF'),
            'color_svg' => $this->helionix->get('helionix::helionix:color_svg', '#FFFFFF'),
            'color_label' => $this->helionix->get('helionix::helionix:color_label', '#F2F2F2'),
            'color_input' => $this->helionix->get('helionix::helionix:color_input', '#F2F2F2'),
            'color_p' => $this->helionix->get('helionix::helionix:color_p', '#EBEBEB'),
            'color_a' => $this->helionix->get('helionix::helionix:color_a', '#EBEBEB'),
            'color_span' => $this->helionix->get('helionix::helionix:color_span', '#E2E2E2'),
            'color_code' => $this->helionix->get('helionix::helionix:color_code', '#E2E2E2'),
            'color_strong' => $this->helionix->get('helionix::helionix:color_strong', '#E2E2E2'),
            'color_invalid' => $this->helionix->get('helionix::helionix:color_invalid', '#FF3A62'),
            
            'dash_layout' => $this->helionix->get('helionix::helionix:dash_layout', 1),
            'dash_billing_status' => $this->helionix->get('helionix::helionix:dash_billing_status', true),
            'dash_billing_url' => $this->helionix->get('helionix::helionix:dash_billing_url', 'https://flydev.one'),
            'dash_billing_blank' => $this->helionix->get('helionix::helionix:dash_billing_blank', true),
            'dash_website_status' => $this->helionix->get('helionix::helionix:dash_website_status', true),
            'dash_website_url' => $this->helionix->get('helionix::helionix:dash_website_url', 'https://flydev.one'),
            'dash_website_blank' => $this->helionix->get('helionix::helionix:dash_website_blank', true),
            'dash_support_status' => $this->helionix->get('helionix::helionix:dash_support_status', true),
            'dash_support_url' => $this->helionix->get('helionix::helionix:dash_support_url', 'https://dsc.flydev.one'),
            'dash_support_blank' => $this->helionix->get('helionix::helionix:dash_support_blank', true),
            'dash_uptime_status' => $this->helionix->get('helionix::helionix:dash_uptime_status', true),
            'dash_uptime_url' => $this->helionix->get('helionix::helionix:dash_uptime_url', '/uptime'),
            'dash_uptime_blank' => $this->helionix->get('helionix::helionix:dash_uptime_blank', false),

            'alert_type' => $this->helionix->get('helionix::helionix:alert_type', 'information'),
            'alert_clossable' => $this->helionix->get('helionix::helionix:alert_clossable', false),
            'alert_message' => $this->helionix->get('helionix::helionix:alert_message', 'Easily customize your Pterodactyl panel with the Helionix Theme'),
            'alert_color_information' => $this->helionix->get('helionix::helionix:alert_color_information', '#589AFC'),
            'alert_color_update' => $this->helionix->get('helionix::helionix:alert_color_update', '#45AF45'),
            'alert_color_warning' => $this->helionix->get('helionix::helionix:alert_color_warning', '#DF5438'),
            'alert_color_error' => $this->helionix->get('helionix::helionix:alert_color_error', '#D53F3F'),

            'announcements_status' => $this->helionix->get('helionix::helionix:announcements_status', true),

            'uptime_nodes_status' => $this->helionix->get('helionix::helionix:uptime_nodes_status', true),
            'uptime_nodes_unit' => $this->helionix->get('helionix::helionix:uptime_nodes_unit', 'percent'),

            'layout_console' => $this->helionix->get('helionix::helionix:layout_console', 1),
            'bar_cpu' => $this->helionix->get('helionix::helionix:bar_cpu', true),
            'bar_memory' => $this->helionix->get('helionix::helionix:bar_memory', true),
            'bar_disk' => $this->helionix->get('helionix::helionix:bar_disk', true),

            'auth_title' => $this->helionix->get('helionix::helionix:authentication:title', 'Helionix'),
            'auth_description' => $this->helionix->get('helionix::helionix:authentication:description', 'Easily customize your Pterodactyl panel with the Helionix Theme'),
            'auth_layout' => $this->helionix->get('helionix::helionix:authentication:layout', 1),
            'auth_register_status' => $this->helionix->get('helionix::helionix:authentication:register:status', true),
            // google auth
            'auth_google_status' => $this->helionix->get('helionix::helionix:authentication:google:status', false),
            'auth_google_client_id' => $this->helionix->get('helionix::helionix:authentication:google:client_id', ''),
            'auth_google_client_secret' => $this->helionix->get('helionix::helionix:authentication:google:client_secret', ''),
            'auth_google_redirect' => $this->helionix->get('helionix::helionix:authentication:google:redirect', ''),
            // discord auth
            'auth_discord_status' => $this->helionix->get('helionix::helionix:authentication:discord:status', false),
            'auth_discord_client_id' => $this->helionix->get('helionix::helionix:authentication:discord:client_id', ''),
            'auth_discord_client_secret' => $this->helionix->get('helionix::helionix:authentication:discord:client_secret', ''),
            'auth_discord_redirect' => $this->helionix->get('helionix::helionix:authentication:discord:redirect', ''),
            // github auth
            'auth_github_status' => $this->helionix->get('helionix::helionix:authentication:github:status', false),
            'auth_github_client_id' => $this->helionix->get('helionix::helionix:authentication:github:client_id', ''),
            'auth_github_client_secret' => $this->helionix->get('helionix::helionix:authentication:github:client_secret', ''),
            'auth_github_redirect' => $this->helionix->get('helionix::helionix:authentication:github:redirect', ''),
        ]);
    }
}
