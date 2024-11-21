import { action, Action } from 'easy-peasy';

export interface HelionixSettings {
    logo: string;
    favicon: string;
    logo_only: boolean;
    logo_height: string;
    
    dash_layout: number;
    dash_billing_status: boolean;
    dash_billing_url: string;
    dash_billing_blank: boolean;
    dash_website_status: boolean;
    dash_website_url: string;
    dash_website_blank: boolean;
    dash_support_status: boolean;
    dash_support_url: string;
    dash_support_blank: boolean;
    dash_uptime_status: boolean;
    dash_uptime_url: string;
    dash_uptime_blank: boolean;

    alert_type: string;
    alert_clossable: boolean;
    alert_message: string;

    announcements_status: boolean;

    uptime_nodes_status: boolean;
    uptime_nodes_unit: string;

    layout_console: number;
    bar_cpu: boolean;
    bar_memory: boolean;
    bar_disk: boolean;

    auth_title: string;
    auth_description: string;
    auth_layout: number;
    auth_register_status: boolean;
    auth_google_status: boolean;
    auth_discord_status: boolean;
    auth_github_status: boolean;
}

export interface HelionixStore {
    data?: HelionixSettings;
    setSettings: Action<HelionixStore, HelionixSettings>;
}

const helionix: HelionixStore = {
    data: undefined,

    setSettings: action((state, payload) => {
        state.data = payload;
    }),
};

export default helionix;