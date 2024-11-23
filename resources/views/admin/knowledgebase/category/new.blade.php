{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}
@extends('layouts.admin')

@section('title')
    Knowledgebase
@endsection

@section('content-header')
    <h1>Knowledgebase<small>Here you can see information.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li class="active">Knowledgebase</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <form action="{{ route('admin.knowledgebase.category.create') }}" method="POST">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Category Creator</h3>
                </div>
                <div class="box-body">
					<div class="form-group">
                        <label for="name" class="control-label">Name</label>
                        <div>
                            <input type="text" name="name" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="control-label">Description</label>
                        <div>
                            <textarea name="description" id="description" rows="4" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! csrf_field() !!}
                        <button type="submit" class="btn btn-primary pull-right">Create</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
