{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}
@extends('layouts.admin')

@section('title')
    {{ $node->name }}: Settings
@endsection

@section('content-header')
    <h1>{{ $node->name }}<small>Configure your node settings.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li><a href="{{ route('admin.nodes') }}">Nodes</a></li>
        <li><a href="{{ route('admin.nodes.view', $node->id) }}">{{ $node->name }}</a></li>
        <li class="active">Downtime</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom nav-tabs-floating">
            <ul class="nav nav-tabs">
                <li><a href="{{ route('admin.nodes.view', $node->id) }}">About</a></li>
				<li><a href="{{ route('admin.nodes.view.settings', $node->id) }}">Settings</a></li>
                <li><a href="{{ route('admin.nodes.view.configuration', $node->id) }}">Configuration</a></li>
                <li><a href="{{ route('admin.nodes.view.allocation', $node->id) }}">Allocation</a></li>
                <li><a href="{{ route('admin.nodes.view.servers', $node->id) }}">Servers</a></li>
				<li class="active"><a href="{{ route('admin.nodes.view.downtime', $node->id) }}">Downtime</a></li>
            </ul>
        </div>
    </div>
</div>
<form action="{{ route('admin.nodes.view.downtime.update', $node->id) }}" method="POST">
	<div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Downtime Settings</h3>
                </div>
				<div class="box-body">
					<div class="form-group">
						<label for="downtime">Downtime</label>
						<div>
							<select name="downtime" class="form-control">
								<option value="1" @if($node->has_downtime === 1) selected @endif>Yes</option>
								<option value="0" @if($node->has_downtime === 0) selected @endif>No</option>
							</select>
							<p class="text-muted"><small>Display the downtime message?</small></p>
						</div>
					</div>
					<div class="form-group">
						<label for="start">Start Date</label>
						<input type="datetime-local" id="start" name="start" class="form-control" @if(isset($node->downtime_start)) value="{{ $node->downtime_start }}" @endif>
						<p class="text-muted"><small>The date and time the downtime will begin.</small></p>
					</div>
					<div class="form-group">
						<label for="end">End Date</label>
						<input type="datetime-local" id="end" name="end" class="form-control" @if(isset($node->downtime_end)) value="{{ $node->downtime_end }}" @endif>
						<p class="text-muted"><small>The date and time the downtime will edit</small></p>
					</div>
				</div>
				<div class="box-footer">
					{!! csrf_field() !!}
					<input type="hidden" name="node_id" value="{{ $node->id }}">
					<button class="btn btn-success pull-right" type="submit">Save</button>
				</div>
			</div>
		</div>
	</div>
</form>
@endsection
