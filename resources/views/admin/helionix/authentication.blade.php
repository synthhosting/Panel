@extends('layouts.helionix')

@section('title')
    Helionix Authentication
@endsection

@section('content')
    <h3>Authentication Settings</h3><p>Configure authentication Helionix Theme.</p>
    <form action="{{ route('admin.helionix.authentication') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="helionix:authentication:title" class="control-label">Title</label>
            <div>
                <input type="text" id="helionix:authentication:title" name="helionix:authentication:title" class="form-control" value="{{ old('helionix:authentication:title', $auth_title) }}" />
                <p class="text-muted"><small>The title for authentication page.</small></p>
            </div>
        </div>
        <div class="form-group">
            <label for="helionix:authentication:description" class="control-label">Description</label>
            <div>
                <textarea rows="4" id="helionix:authentication:description" name="helionix:authentication:description" class="form-control">{{ old('helionix:authentication:description', $auth_description) }}</textarea>
                <p class="text-muted"><small>The description for authentication page.</small></p>
            </div>
        </div>
        <div class="form-group">
            <label for="helionix:authentication:layout" class="control-label">Layout</label>
            <div class="option-group">
                <div class="option">
                    <input type="radio" id="auth_layout_1" name="helionix:authentication:layout" value="1" class="hidden" {{ $auth_layout == 1 ? "checked" : "" }}>
                    <label for="auth_layout_1" class="option-radio">
                        <img src="/helionix/auth/layout1.png">
                    </label>
                </div>
                <div class="option">
                    <input type="radio" id="auth_layout_2" name="helionix:authentication:layout" value="2" class="hidden" {{ $auth_layout == 2 ? "checked" : "" }}>
                    <label for="auth_layout_2" class="option-radio">
                        <img src="/helionix/auth/layout2.png">
                    </label>
                </div>
                <div class="option">
                    <input type="radio" id="auth_layout_3" name="helionix:authentication:layout" value="3" class="hidden" {{ $auth_layout == 3 ? "checked" : "" }}>
                    <label for="auth_layout_3" class="option-radio">
                        <img src="/helionix/auth/layout3.png">
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="helionix:authentication:register:status" class="control-label">Register form</label>
            <div>
                <select class="form-control" name="helionix:authentication:register:status" value="{{ old('helionix:authentication:register:status', $auth_register_status) }}">
                    <option value="1">Enable</option>
                    <option value="0" @if(old('helionix:authentication:register:status', $auth_register_status) == '0') selected @endif>Disable</option>
                </select>
                <p class="text-muted"><small>Enable or disable register form.</small></p>
            </div>
        </div>
        <div class="form-group">
            <label for="helionix:authentication:google:status" class="control-label">Google Authentication</label>
            <div>
                <select class="form-control" name="helionix:authentication:google:status" value="{{ old('helionix:authentication:google:status', $auth_google_status) }}">
                    <option value="1">Enable</option>
                    <option value="0" @if(old('helionix:authentication:google:status', $auth_google_status) == '0') selected @endif>Disable</option>
                </select>
            </div>
            <div>
                <p class="text-muted"><small>The client ID for Google Authentication.</small></p>
                <input type="text" id="helionix:authentication:google:client_id" name="helionix:authentication:google:client_id" class="form-control" value="{{ old('helionix:authentication:google:client_id', $auth_google_client_id) }}" />
            </div>
            <div>
                <p class="text-muted"><small>The client secret for Google Authentication.</small></p>
                <input type="text" id="helionix:authentication:google:client_secret" name="helionix:authentication:google:client_secret" class="form-control" value="{{ old('helionix:authentication:google:client_secret', $auth_google_client_secret) }}" />
            </div>
            <div>
                <p class="text-muted"><small>The redirect URI for Google Authentication.</small></p>
                <input type="text" id="helionix:authentication:google:redirect" name="helionix:authentication:google:redirect" class="form-control" value="{{ old('helionix:authentication:google:redirect', $auth_google_redirect) }}" />
            </div>
        </div>
        <div class="form-group">
            <label for="helionix:authentication:discord:status" class="control-label">Discord Authentication</label>
            <div>
                <select class="form-control" name="helionix:authentication:discord:status" value="{{ old('helionix:authentication:discord:status', $auth_discord_status) }}">
                    <option value="1">Enable</option>
                    <option value="0" @if(old('helionix:authentication:discord:status', $auth_discord_status) == '0') selected @endif>Disable</option>
                </select>
            </div>
            <div>
                <p class="text-muted"><small>The client ID for Discord Authentication.</small></p>
                <input type="text" id="helionix:authentication:discord:client_id" name="helionix:authentication:discord:client_id" class="form-control" value="{{ old('helionix:authentication:discord:client_id', $auth_discord_client_id) }}" />
            </div>
            <div>
                <p class="text-muted"><small>The client secret for Discord Authentication.</small></p>
                <input type="text" id="helionix:authentication:discord:client_secret" name="helionix:authentication:discord:client_secret" class="form-control" value="{{ old('helionix:authentication:discord:client_secret', $auth_discord_client_secret) }}" />
            </div>
            <div>
                <p class="text-muted"><small>The redirect URI for Discord Authentication.</small></p>
                <input type="text" id="helionix:authentication:discord:redirect" name="helionix:authentication:discord:redirect" class="form-control" value="{{ old('helionix:authentication:discord:redirect', $auth_discord_redirect) }}" />
            </div>
        </div>
        <div class="form-group">
            <label for="helionix:authentication:github:status" class="control-label">Github Authentication</label>
            <div>
                <select class="form-control" name="helionix:authentication:github:status" value="{{ old('helionix:authentication:github:status', $auth_github_status) }}">
                    <option value="1">Enable</option>
                    <option value="0" @if(old('helionix:authentication:github:status', $auth_github_status) == '0') selected @endif>Disable</option>
                </select>
            </div>
            <div>
                <p class="text-muted"><small>The client ID for Github Authentication.</small></p>
                <input type="text" id="helionix:authentication:github:client_id" name="helionix:authentication:github:client_id" class="form-control" value="{{ old('helionix:authentication:github:client_id', $auth_github_client_id) }}" />
            </div>
            <div>
                <p class="text-muted"><small>The client secret for Github Authentication.</small></p>
                <input type="text" id="helionix:authentication:github:client_secret" name="helionix:authentication:github:client_secret" class="form-control" value="{{ old('helionix:authentication:github:client_secret', $auth_github_client_secret) }}" />
            </div>
            <div>
                <p class="text-muted"><small>The redirect URI for Github Authentication.</small></p>
                <input type="text" id="helionix:authentication:github:redirect" name="helionix:authentication:github:redirect" class="form-control" value="{{ old('helionix:authentication:github:redirect', $auth_github_redirect) }}" />
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
    <div class="no-preview">No preview available</div>
@endsection