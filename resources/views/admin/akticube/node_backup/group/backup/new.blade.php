@extends('layouts.admin')

@section('title')
    ric-rac | Creating a backup
@endsection

@section('content-header')
    <h1>Node Backup<small>Create a new backup.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li><a href="https://discord.gg/RJ2A8yYS2m" target="_blank">ric-rac</a></li>
        <li><a href="{{ route('admin.akticube.node-backup') }}">Node Backup</a></li>
        <li><a href="{{ route('admin.akticube.node-backup.group.view', $backup_group->id) }}">{{ $backup_group->name }}</a></li>
        <li class="active">Create a new backup</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <form method="post">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Characteristics</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="name" class="control-label">Name <span class="field-required"></span></label>
                            <div>
                                <input type="text" id="name" autocomplete="off" name="name" class="form-control" value="{{ old('name') }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! csrf_field() !!}
                        <input type="submit" value="Create backup" class="btn btn-success btn-sm">
                        <a href="{{ route('admin.akticube.node-backup.group.view', $backup_group->id) }}" class="btn btn-default btn-sm">Go Back</a>
                    </div>
                </div>
            </div>
        </form>
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Informations</h3>
                </div>
                <div class="box-body">
                    <div class="form-group col-md-12">
                        <h4 class="text-red">IMPORTANT !</h4>
                        <p>If you need help for anything, please join our <a href="https://discord.gg/RJ2A8yYS2m" target="_blank">Discord</a>.</p>
                        <p>Don't forget to read the <a href="https://github.com/AktiCube/themes-and-addons-documentation/wiki/Installation-(Node Backup)#configuration" target="_blank">documentation</a>!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
