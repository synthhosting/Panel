@extends('layouts.helionix')

@section('title')
    Helionix Server
@endsection

@section('content')
    <h3>Server Settings</h3><p>Configure server Helionix Theme.</p>
    <form action="{{ route('admin.helionix.server') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="helionix:layout_console" class="control-label">Layout Console</label>
            <div class="option-group">
                <div class="option">
                    <input type="radio" id="layout_console_1" name="helionix:layout_console" value="1" class="hidden" {{ $layout_console == 1 ? "checked" : "" }}>
                    <label for="layout_console_1" class="option-radio">
                        <img src="/helionix/server/layout1.png">
                    </label>
                </div>
                <div class="option">
                    <input type="radio" id="layout_console_2" name="helionix:layout_console" value="2" class="hidden" {{ $layout_console == 2 ? "checked" : "" }}>
                    <label for="layout_console_2" class="option-radio">
                        <img src="/helionix/server/layout2.png">
                    </label>
                </div>
                <div class="option">
                    <input type="radio" id="layout_console_3" name="helionix:layout_console" value="3" class="hidden" {{ $layout_console == 3 ? "checked" : "" }}>
                    <label for="layout_console_3" class="option-radio">
                        <img src="/helionix/server/layout3.png">
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Cpu Bar</label>
            <div>
                <select class="form-control" name="helionix:bar_cpu" value="{{ old('helionix:bar_cpu', $bar_cpu) }}">
                    <option value="1">Enable</option>
                    <option value="0" @if(old('helionix:bar_cpu', $bar_cpu) == '0') selected @endif>Disable</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Memory Bar</label>
            <div>
                <select class="form-control" name="helionix:bar_memory" value="{{ old('helionix:bar_memory', $bar_memory) }}">
                    <option value="1">Enable</option>
                    <option value="0" @if(old('helionix:bar_memory', $bar_memory) == '0') selected @endif>Disable</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Disk Bar</label>
            <div>
                <select class="form-control" name="helionix:bar_disk" value="{{ old('helionix:bar_disk', $bar_disk) }}">
                    <option value="1">Enable</option>
                    <option value="0" @if(old('helionix:bar_disk', $bar_disk) == '0') selected @endif>Disable</option>
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
    <div class="no-preview">No preview available</div>
@endsection