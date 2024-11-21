<?php

namespace Pterodactyl\Http\Requests\Admin\Helionix;

use Pterodactyl\Http\Requests\Admin\AdminFormRequest;

class ColorSettingsRequest extends AdminFormRequest
{

    public function rules(): array
    {
        return [
            'helionix:color_1' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:color_2' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:color_3' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:color_4' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:color_5' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:color_6' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:color_console' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:color_editor' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:button_primary' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:button_primary_hover' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:button_secondary' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:button_secondary_hover' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:button_danger' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:button_danger_hover' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:color_h1' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:color_svg' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:color_label' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:color_input' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:color_p' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:color_a' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:color_span' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:color_code' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:color_strong' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'helionix:color_invalid' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ];
    }
}