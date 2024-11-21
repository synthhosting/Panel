@extends('layouts.helionix')

@section('title')
    Helionix General
@endsection

@section('content')
    <h3>General Settings</h3><p>Configure general Helionix Theme.</p>
    <form action="{{ route('admin.helionix.general') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="helionix:logo" class="control-label">Main Logo</label>
            <div>
                <input type="text" id="helionix:logo" name="helionix:logo" class="form-control" value="{{ old('helionix:logo', $logo) }}" />
                <p class="text-muted"><small>The logo path or url for sidebar and auth form.</small></p>
            </div>
        </div>
        <div class="form-group">
            <label for="helionix:favicon" class="control-label">Favicon Logo</label>
            <div>
                <input type="text" id="helionix:favicon" name="helionix:favicon" class="form-control" value="{{ old('helionix:favicon', $favicon) }}" />
                <p class="text-muted"><small>The logo path or url for favicon.</small></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Logo Only</label>
            <div>
                <select class="form-control" name="helionix:logo_only" value="{{ old('helionix:logo_only', $logo_only) }}">
                    <option value="0">Disable</option>
                    <option value="1" @if(old('helionix:logo_only', $logo_only) == '1') selected @endif>Enable</option>
                </select>
                <p class="text-muted"><small>Enable or disable the text next to the navbar logo.</small></p>
            </div>
        </div>
        <div class="form-group">
            <label for="helionix:logo_height" class="control-label">Logo Height</label>
            <div>
                <input type="text" id="helionix:logo_height" name="helionix:logo_height" class="form-control" value="{{ old('helionix:logo_height', $logo_height) }}" />
                <p class="text-muted"><small>Set the height of the logo displayed in the navbar.</small></p>
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