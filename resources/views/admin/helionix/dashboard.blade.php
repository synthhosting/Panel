@extends('layouts.helionix')

@section('title')
    Helionix Dashboard
@endsection

@section('content')
    <h3>Dashboard Settings</h3><p>Configure dashboard Helionix Theme.</p>
    <form action="{{ route('admin.helionix.dashboard') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="helionix:dash_layout" class="control-label">Layout Server</label>
            <div class="option-group">
                <div class="option">
                    <input type="radio" id="dash_layout_1" name="helionix:dash_layout" value="1" class="hidden" {{ $dash_layout == 1 ? "checked" : "" }}>
                    <label for="dash_layout_1" class="option-radio">
                        <img src="/helionix/dashboard/layout1.png">
                    </label>
                </div>
                <div class="option">
                    <input type="radio" id="dash_layout_2" name="helionix:dash_layout" value="2" class="hidden" {{ $dash_layout == 2 ? "checked" : "" }}>
                    <label for="dash_layout_2" class="option-radio">
                        <img src="/helionix/dashboard/layout2.png">
                    </label>
                </div>
                <div class="option">
                    <input type="radio" id="dash_layout_3" name="helionix:dash_layout" value="3" class="hidden" {{ $dash_layout == 3 ? "checked" : "" }}>
                    <label for="dash_layout_3" class="option-radio">
                        <img src="/helionix/dashboard/layout3.png">
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Billing</label>
            <div>
                <select class="form-control" name="helionix:dash_billing_status" value="{{ old('helionix:dash_billing_status', $dash_billing_status) }}">
                    <option value="1">Enable</option>
                    <option value="0" @if(old('helionix:dash_billing_status', $dash_billing_status) == '0') selected @endif>Disable</option>
                </select>
            </div>
            <div>
                <p class="text-muted"><small>The URL of billing.</small></p>
                <input type="text" id="helionix:dash_billing_url" name="helionix:dash_billing_url" class="form-control" value="{{ old('helionix:dash_billing_url', $dash_billing_url) }}" />
            </div>
            <div>
                <p class="text-muted"><small>Enable _blank target URL.</small></p>
                <select class="form-control" name="helionix:dash_billing_blank" value="{{ old('helionix:dash_billing_blank', $dash_billing_blank) }}">
                    <option value="1">Enable</option>
                    <option value="0" @if(old('helionix:dash_billing_blank', $dash_billing_blank) == '0') selected @endif>Disable</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Website</label>
            <div>
                <select class="form-control" name="helionix:dash_website_status" value="{{ old('helionix:dash_website_status', $dash_website_status) }}">
                    <option value="1">Enable</option>
                    <option value="0" @if(old('helionix:dash_website_status', $dash_website_status) == '0') selected @endif>Disable</option>
                </select>
            </div>
            <div>
                <p class="text-muted"><small>The URL of website.</small></p>
                <input type="text" id="helionix:dash_website_url" name="helionix:dash_website_url" class="form-control" value="{{ old('helionix:dash_website_url', $dash_website_url) }}" />
            </div>
            <div>
                <p class="text-muted"><small>Enable _blank target URL.</small></p>
                <select class="form-control" name="helionix:dash_website_blank" value="{{ old('helionix:dash_website_blank', $dash_website_blank) }}">
                    <option value="1">Enable</option>
                    <option value="0" @if(old('helionix:dash_website_blank', $dash_website_blank) == '0') selected @endif>Disable</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Support</label>
            <div>
                <select class="form-control" name="helionix:dash_support_status" value="{{ old('helionix:dash_support_status', $dash_support_status) }}">
                    <option value="1">Enable</option>
                    <option value="0" @if(old('helionix:dash_support_status', $dash_support_status) == '0') selected @endif>Disable</option>
                </select>
            </div>
            <div>
                <p class="text-muted"><small>The URL of support.</small></p>
                <input type="text" id="helionix:dash_support_url" name="helionix:dash_support_url" class="form-control" value="{{ old('helionix:dash_support_url', $dash_support_url) }}" />
            </div>
            <div>
                <p class="text-muted"><small>Enable _blank target URL.</small></p>
                <select class="form-control" name="helionix:dash_support_blank" value="{{ old('helionix:dash_support_blank', $dash_support_blank) }}">
                    <option value="1">Enable</option>
                    <option value="0" @if(old('helionix:dash_support_blank', $dash_support_blank) == '0') selected @endif>Disable</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Uptime</label>
            <div>
                <select class="form-control" name="helionix:dash_uptime_status" value="{{ old('helionix:dash_uptime_status', $dash_uptime_status) }}">
                    <option value="1">Enable</option>
                    <option value="0" @if(old('helionix:dash_uptime_status', $dash_uptime_status) == '0') selected @endif>Disable</option>
                </select>
            </div>
            <div>
                <p class="text-muted"><small>The URL of uptime.</small></p>
                <input type="text" id="helionix:dash_uptime_url" name="helionix:dash_uptime_url" class="form-control" value="{{ old('helionix:dash_uptime_url', $dash_uptime_url) }}" />
            </div>
            <div>
                <p class="text-muted"><small>Enable _blank target URL.</small></p>
                <select class="form-control" name="helionix:dash_uptime_blank" value="{{ old('helionix:dash_uptime_blank', $dash_uptime_blank) }}">
                    <option value="1" @if(old('helionix:dash_uptime_blank', $dash_uptime_blank) == '1') selected @endif>Enable</option>
                    <option value="0" @if(old('helionix:dash_uptime_blank', $dash_uptime_blank) == '0') selected @endif>Disable</option>
                </select>
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