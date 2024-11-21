@extends('layouts.helionix')

@section('title')
    Helionix Uptime
@endsection

@section('content')
    <h3>Uptime Settings</h3><p>Configure uptime nodes Helionix Theme.</p>
    <form action="{{ route('admin.helionix.uptime') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="helionix:uptime_nodes_status" class="control-label">Uptime Status</label>
            <select class="form-control" name="helionix:uptime_nodes_status" value="{{ old('helionix:uptime_nodes_status', $uptime_nodes_status) }}">
                <option value="1">Enable</option>
                <option value="0" @if(old('helionix:uptime_nodes_status', $uptime_nodes_status) == '0') selected @endif>Disable</option>
            </select>
            <p class="text-muted"><small>Enable or disable uptime nodes page.</small></p>
        </div>
        <div class="form-group">
            <label for="helionix:uptime_nodes_unit" class="control-label">Uptime Unit</label>
            <select class="form-control" name="helionix:uptime_nodes_unit" value="{{ old('helionix:uptime_nodes_unit', $uptime_nodes_unit) }}">
                <option value="percent">Percent</option>
                <option value="mb" @if(old('helionix:uptime_nodes_unit', $uptime_nodes_unit) == 'mb') selected @endif>Mb</option>
                <option value="gb" @if(old('helionix:uptime_nodes_unit', $uptime_nodes_unit) == 'gb') selected @endif>Gb</option>
                <option value="tb" @if(old('helionix:uptime_nodes_unit', $uptime_nodes_unit) == 'tb') selected @endif>Tb</option>
                <option value="none" @if(old('helionix:uptime_nodes_unit', $uptime_nodes_unit) == 'none') selected @endif>None</option>
            </select>
            <p class="text-muted"><small>Displays the memory and disk value with the selected unit.</small></p>
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
    @if($uptime_nodes_status)
        <iframe src="/uptime" frameborder="0"></iframe>
    @else
        <div class="no-preview">No preview available</div>
    @endif
@endsection