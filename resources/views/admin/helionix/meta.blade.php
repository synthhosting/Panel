@extends('layouts.helionix')

@section('title')
    Helionix Meta
@endsection

@section('content')
    <h3>Meta Settings</h3><p>Configure meta-tag Helionix Theme.</p>
    <form action="{{ route('admin.helionix.meta') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="helionix:meta_logo" class="control-label">Meta Logo</label>
            <div>
                <input type="text" id="helionix:meta_logo" name="helionix:meta_logo" class="form-control" value="{{ old('helionix:meta_logo', $meta_logo) }}" />
                <p class="text-muted"><small>The logo of Meta-Tag.</small></p>
            </div>
        </div>
        <div class="form-group">
            <label for="helionix:meta_title" class="control-label">Meta Title</label>
            <div>
                <input type="text" id="helionix:meta_title" name="helionix:meta_title" class="form-control" value="{{ old('helionix:meta_title', $meta_title) }}" />
                <p class="text-muted"><small>The title of Meta-Tag.</small></p>
            </div>
        </div>
        <div class="form-group">
            <label for="helionix:meta_description" class="control-label">Meta Description</label>
            <div>
                <input type="text" id="helionix:meta_description" name="helionix:meta_description" class="form-control" value="{{ old('helionix:meta_description', $meta_description) }}" />
                <p class="text-muted"><small>The description of Meta-Tag.</small></p>
            </div>
        </div>
        <div class="form-group">
            <label for="helionix:meta_color" class="control-label">Meta Color</label>
            <div class="color">
                <input type="text" id="helionix:meta_color" name="helionix:meta_color" class="color-option" placeholder="#000000" value="{{ old('helionix:meta_color', $meta_color) }}" data-coloris="" onclick="focusDash()"/>
                <p class="text-muted"><small>The color of Meta-Tag.</small></p>
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