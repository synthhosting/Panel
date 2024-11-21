@extends('layouts.helionix')

@section('title')
    Helionix Alert
@endsection

@section('content')
    <h3>Alert Settings</h3><p>Configure alert Helionix Theme.</p>
    <form action="{{ route('admin.helionix.alert') }}" method="POST">
        @csrf
        <div class="form-group">
            <div class="form-group">
                <label for="helionix:alert_type" class="control-label">Alert Type</label>
                <select class="form-control" name="helionix:alert_type" value="{{ old('helionix:alert_type', $alert_type) }}">
                    <option value="information">Information</option>
                    <option value="update" @if(old('helionix:alert_type', $alert_type) == 'update') selected @endif>Update</option>
                    <option value="warning" @if(old('helionix:alert_type', $alert_type) == 'warning') selected @endif>Warning</option>
                    <option value="error" @if(old('helionix:alert_type', $alert_type) == 'error') selected @endif>Error</option>
                    <option value="disable" @if(old('helionix:alert_type', $alert_type) == 'disable') selected @endif>Disable</option>
                </select>
                <p class="text-muted"><small>Select the alert type.</small></p>
            </div>
            <label for="helionix:alert_clossable" class="control-label">Alert Clossable</label>
            <select class="form-control" name="helionix:alert_clossable" value="{{ old('helionix:alert_clossable', $alert_clossable) }}">
                <option value="1">Enable</option>
                <option value="0" @if(old('helionix:alert_clossable', $alert_clossable) == '0') selected @endif>Disable</option>
            </select>
            <p class="text-muted"><small>Enable or disable alert clossable button.</small></p>
        </div>
        <div class="form-group">
            <label for="helionix:alert_message" class="control-label">Alert Message</label>
            <div>
                <textarea rows="4" id="helionix:alert_message" name="helionix:alert_message" class="form-control">{{ old('helionix:alert_message', $alert_message) }}</textarea>
                <p class="text-muted"><small>The message of allert.</small></p>
            </div>
        </div>
        <div class="form-group">    
            <label class="control-label">Alert Color</label>
            <div class="color-group">
                <div class="color">
                    <input type="text" id="helionix:alert_color_information" name="helionix:alert_color_information" class="color-option" placeholder="#000000" value="{{ old('helionix:alert_color_information', $alert_color_information) }}" data-coloris="" onclick="focusDash()"/>
                    <p class="text-muted"><small>The color of Alert Information.</small></p>
                </div>
                <div class="color">
                    <input type="text" id="helionix:alert_color_update" name="helionix:alert_color_update" class="color-option" placeholder="#000000" value="{{ old('helionix:alert_color_update', $alert_color_update) }}" data-coloris="" onclick="focusDash()"/>
                    <p class="text-muted"><small>The color of Alert Update.</small></p>
                </div>
                <div class="color">
                    <input type="text" id="helionix:alert_color_warning" name="helionix:alert_color_warning" class="color-option" placeholder="#000000" value="{{ old('helionix:alert_color_warning', $alert_color_warning) }}" data-coloris="" onclick="focusDash()"/>
                    <p class="text-muted"><small>The color of Alert Warning.</small></p>
                </div>
                <div class="color">
                    <input type="text" id="helionix:alert_color_error" name="helionix:alert_color_error" class="color-option" placeholder="#000000" value="{{ old('helionix:alert_color_error', $alert_color_error) }}" data-coloris="" onclick="focusDash()"/>
                    <p class="text-muted"><small>The color of Alert Error.</small></p>
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