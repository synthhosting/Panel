@extends('layouts.admin')

@section('title')
    Permission Managment
@endsection

@section('content-header')
    <h1>Create Role</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li class="active">Permission Managment</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <form method="POST" action="{{ route('admin.permissions.new') }}">
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
                                    <input type="radio" id="r1" name="settings" value="1">
                                    <label for="r1">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw1" name="settings" value="2">
                                    <label for="rw1">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n1" name="settings" value="0" checked>
                                    <label for="n1">None</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-3 strong">Application API</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r2" name="api" value="1">
                                    <label for="r2">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw2" name="api" value="2">
                                    <label for="rw2">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n2" name="api" value="0" checked>
                                    <label for="n2">None</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-3 strong">Permission Managment</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r3" name="permissions" value="1">
                                    <label for="r3">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw3" name="permissions" value="2">
                                    <label for="rw3">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n3" name="permissions" value="0" checked>
                                    <label for="n3">None</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-3 strong">Databases</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r4" name="databases" value="1">
                                    <label for="r4">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw4" name="databases" value="2">
                                    <label for="rw4">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n4" name="databases" value="0" checked>
                                    <label for="n4">None</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-3 strong">Locations</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r5" name="locations" value="1">
                                    <label for="r5">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw5" name="locations" value="2">
                                    <label for="rw5">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n5" name="locations" value="0" checked>
                                    <label for="n5">None</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-3 strong">Nodes</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r6" name="nodes" value="1">
                                    <label for="r6">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw6" name="nodes" value="2">
                                    <label for="rw6">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n6" name="nodes" value="0" checked>
                                    <label for="n6">None</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-3 strong">Servers</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r7" name="servers" value="1">
                                    <label for="r7">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw7" name="servers" value="2">
                                    <label for="rw7">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n7" name="servers" value="0" checked>
                                    <label for="n7">None</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-3 strong">Users</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r8" name="users" value="1">
                                    <label for="r8">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw8" name="users" value="2">
                                    <label for="rw8">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n8" name="users" value="0" checked>
                                    <label for="n8">None</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-3 strong">Mounts</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r9" name="mounts" value="1">
                                    <label for="r9">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw9" name="mounts" value="2">
                                    <label for="rw9">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n9" name="mounts" value="0" checked>
                                    <label for="n9">None</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-3 strong">Nests</td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="r10" name="nests" value="1">
                                    <label for="r10">Read</label>
                                </td>
                                <td class="col-sm-3 radio radio-primary text-center">
                                    <input type="radio" id="rw10" name="nests" value="2">
                                    <label for="rw10">Read &amp; Write</label>
                                </td>
                                <td class="col-sm-3 radio text-center">
                                    <input type="radio" id="n10" name="nests" value="0" checked>
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
                            <input id="name" type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input id="color" type="color" name="color" value="#000000">
                            <label class="control-label" for="color">Role Color</label>
                        </div>
                    </div>
                    <div class="box-footer">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-success btn-sm pull-right">Create Role</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('footer-scripts')
    @parent
    <script>
    </script>
@endsection
