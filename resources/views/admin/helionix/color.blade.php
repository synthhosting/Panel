@extends('layouts.helionix')

@section('title')
    Helionix Color
@endsection

@section('content')
    <h3>Color Settings</h3><p>Configure color Helionix Theme.</p>
    <form action="{{ route('admin.helionix.color') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="control-label">Background</label>
            <div class="color-group">
                <div class="color">
                    <input type="text" id="helionix:color_1" name="helionix:color_1" class="color-option" placeholder="#000000"  value="{{ old('helionix:color_1', $color_1) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <input type="text" id="helionix:color_2" name="helionix:color_2" class="color-option" placeholder="#000000"  value="{{ old('helionix:color_2', $color_2) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <input type="text" id="helionix:color_3" name="helionix:color_3" class="color-option" placeholder="#000000" value="{{ old('helionix:color_3', $color_3) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <input type="text" id="helionix:color_4" name="helionix:color_4" class="color-option" placeholder="#000000" value="{{ old('helionix:color_4', $color_4) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <input type="text" id="helionix:color_5" name="helionix:color_5" class="color-option" placeholder="#000000" value="{{ old('helionix:color_5', $color_5) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <input type="text" id="helionix:color_6" name="helionix:color_6" class="color-option" placeholder="#000000" value="{{ old('helionix:color_6', $color_6) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <p class="text-muted"><small>Color Console</small></p>
                    <input type="text" id="helionix:color_console" name="helionix:color_console" class="color-option" placeholder="#000000" value="{{ old('helionix:color_console', $color_console) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <p class="text-muted"><small>Color Editor</small></p>
                    <input type="text" id="helionix:color_editor" name="helionix:color_editor" class="color-option" placeholder="#000000" value="{{ old('helionix:color_editor', $color_editor) }}" data-coloris="" onclick="focusDash()">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Button</label>
            <div class="color-group">
                <div class="color">
                    <p class="text-muted"><small>Primary</small></p>
                    <input type="text" id="helionix:button_primary" name="helionix:button_primary" class="color-option" placeholder="#000000" value="{{ old('helionix:button_primary', $button_primary) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <p class="text-muted"><small>Primary Hover</small></p>
                    <input type="text" id="helionix:button_primary_hover" name="helionix:button_primary_hover" class="color-option" placeholder="#000000" value="{{ old('helionix:button_primary_hover', $button_primary_hover) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <p class="text-muted"><small>Secondary</small></p>
                    <input type="text" id="helionix:button_secondary" name="helionix:button_secondary" class="color-option" placeholder="#000000" value="{{ old('helionix:button_secondary', $button_secondary) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <p class="text-muted"><small>Secondary Hover</small></p>
                    <input type="text" id="helionix:button_secondary_hover" name="helionix:button_secondary_hover" class="color-option" placeholder="#000000" value="{{ old('helionix:button_secondary_hover', $button_secondary_hover) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <p class="text-muted"><small>Danger</small></p>
                    <input type="text" id="helionix:button_danger" name="helionix:button_danger" class="color-option" placeholder="#000000" value="{{ old('helionix:button_danger', $button_danger) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <p class="text-muted"><small>Danger Hover</small></p>
                    <input type="text" id="helionix:button_danger_hover" name="helionix:button_danger_hover" class="color-option" placeholder="#000000" value="{{ old('helionix:button_danger_hover', $button_danger_hover) }}" data-coloris="" onclick="focusDash()">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Text</label>
            <div class="color-group">
                <div class="color">
                    <p class="text-muted"><small>h1 - h6</small></p>
                    <input type="text" id="helionix:color_h1" name="helionix:color_h1" class="color-option" placeholder="#000000" value="{{ old('helionix:color_h1', $color_h1) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <p class="text-muted"><small>svg (icon)</small></p>
                    <input type="text" id="helionix:color_svg" name="helionix:color_svg" class="color-option" placeholder="#000000" value="{{ old('helionix:color_svg', $color_svg) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <p class="text-muted"><small>label</small></p>
                    <input type="text" id="helionix:color_label" name="helionix:color_label" class="color-option" placeholder="#000000" value="{{ old('helionix:color_label', $color_label) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <p class="text-muted"><small>input</small></p>
                    <input type="text" id="helionix:color_input" name="helionix:color_input" class="color-option" placeholder="#000000" value="{{ old('helionix:color_input', $color_input) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <p class="text-muted"><small>p</small></p>
                    <input type="text" id="helionix:color_p" name="helionix:color_p" class="color-option" placeholder="#000000" value="{{ old('helionix:color_p', $color_p) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <p class="text-muted"><small>a</small></p>
                    <input type="text" id="helionix:color_a" name="helionix:color_a" class="color-option" placeholder="#000000" value="{{ old('helionix:color_a', $color_a) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <p class="text-muted"><small>span</small></p>
                    <input type="text" id="helionix:color_span" name="helionix:color_span" class="color-option" placeholder="#000000" value="{{ old('helionix:color_span', $color_span) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <p class="text-muted"><small>code</small></p>
                    <input type="text" id="helionix:color_code" name="helionix:color_code" class="color-option" placeholder="#000000" value="{{ old('helionix:color_code', $color_code) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <p class="text-muted"><small>strong</small></p>
                    <input type="text" id="helionix:color_strong" name="helionix:color_strong" class="color-option" placeholder="#000000" value="{{ old('helionix:color_strong', $color_strong) }}" data-coloris="" onclick="focusDash()">
                </div>
                <div class="color">
                    <p class="text-muted"><small>invalid</small></p>
                    <input type="text" id="helionix:color_invalid" name="helionix:color_invalid" class="color-option" placeholder="#000000" value="{{ old('helionix:color_invalid', $color_invalid) }}" data-coloris="" onclick="focusDash()">
                </div>
            </div>
        </div>
        @section('button-save')
        <li class="save-btn">
            <a onclick="document.querySelector('form').submit();">
                <i class="fas fa-save"></i>
            </a>
        </li>
        @endsection 
    </form>
@endsection

@section('content-show')
    <iframe src="/" frameborder="0"></iframe>
@endsection