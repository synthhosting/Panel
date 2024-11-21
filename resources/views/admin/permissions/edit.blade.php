@extends('layouts.admin')

@section('title')
    Permission Managment
@endsection

@section('content-header')
    <h1>Edit Role</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li class="active">Permission Managment</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <form method="POST" action="{{ route('admin.permissions.edit', $role->id) }}">
            <div class="col-sm-8 col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Select Permissions</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <td class="col-sm-3 strong">Panel Settings</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r1" name="settings" value="1" @if($role->p_settings == 1) checked @endif>
                                    <label for="r1">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw1" name="settings" value="2" @if($role->p_settings == 2) checked @endif>
                                    <label for="rw1">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n1" name="settings" value="0" @if($role->p_settings == 0) checked @endif>
                                    <label for="n1">None</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-3 strong">Application API</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r2" name="api" value="1" @if($role->p_api == 1) checked @endif>
                                    <label for="r2">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw2" name="api" value="2" @if($role->p_api == 2) checked @endif>
                                    <label for="rw2">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n2" name="api" value="0" @if($role->p_api == 0) checked @endif>
                                    <label for="n2">None</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-3 strong">Permission Managment</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r3" name="permissions" value="1" @if($role->p_permissions == 1) checked @endif>
                                    <label for="r3">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw3" name="permissions" value="2" @if($role->p_permissions == 2) checked @endif>
                                    <label for="rw3">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n3" name="permissions" value="0" @if($role->p_permissions == 0) checked @endif>
                                    <label for="n3">None</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-3 strong">Databases</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r4" name="databases" value="1" @if($role->p_databases == 1) checked @endif>
                                    <label for="r4">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw4" name="databases" value="2" @if($role->p_databases == 2) checked @endif>
                                    <label for="rw4">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n4" name="databases" value="0" @if($role->p_databases == 0) checked @endif>
                                    <label for="n4">None</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-3 strong">Locations</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r5" name="locations" value="1" @if($role->p_locations == 1) checked @endif>
                                    <label for="r5">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw5" name="locations" value="2" @if($role->p_locations == 2) checked @endif>
                                    <label for="rw5">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n5" name="locations" value="0" @if($role->p_locations == 0) checked @endif>
                                    <label for="n5">None</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-3 strong">Nodes</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r6" name="nodes" value="1" @if($role->p_nodes == 1) checked @endif>
                                    <label for="r6">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw6" name="nodes" value="2" @if($role->p_nodes == 2) checked @endif>
                                    <label for="rw6">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n6" name="nodes" value="0" @if($role->p_nodes == 0) checked @endif>
                                    <label for="n6">None</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-3 strong">Servers</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r7" name="servers" value="1" @if($role->p_servers == 1) checked @endif>
                                    <label for="r7">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw7" name="servers" value="2" @if($role->p_servers == 2) checked @endif>
                                    <label for="rw7">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n7" name="servers" value="0" @if($role->p_servers == 0) checked @endif>
                                    <label for="n7">None</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-3 strong">Users</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r8" name="users" value="1" @if($role->p_users == 1) checked @endif>
                                    <label for="r8">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw8" name="users" value="2" @if($role->p_users == 2) checked @endif>
                                    <label for="rw8">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n8" name="users" value="0" @if($role->p_users == 0) checked @endif>
                                    <label for="n8">None</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-3 strong">Mounts</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r9" name="mounts" value="1" @if($role->p_mounts == 1) checked @endif>
                                    <label for="r9">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw9" name="mounts" value="2" @if($role->p_mounts == 2) checked @endif>
                                    <label for="rw9">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n9" name="mounts" value="0" @if($role->p_mounts == 0) checked @endif>
                                    <label for="n9">None</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-3 strong">Nests</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r10" name="nests" value="1" @if($role->p_nests == 1) checked @endif>
                                    <label for="r10">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw10" name="nests" value="2" @if($role->p_nests == 2) checked @endif>
                                    <label for="rw10">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n10" name="nests" value="0" @if($role->p_nests == 0) checked @endif>
                                    <label for="n10">None</label>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label" for="name">Name <span class="field-required"></span></label>
                            <input id="name" type="text" name="name" class="form-control" value="{{ $role->name }}" required>
                        </div>
                        <div class="form-group">
                            <input id="color" type="color" name="color" value="{{ $role->color }}">
                            <label class="control-label" for="color">Role Color</label>
                        </div>
                    </div>
                    <div class="box-footer">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-success btn-sm pull-right">Edit Role</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
