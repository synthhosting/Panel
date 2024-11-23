{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}
@extends('layouts.admin')

@section('title')
    Knowledgebase
@endsection

@section('content-header')
    <h1>Knowledgebase<small>Edit the knowledgebase.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li class="active">Knowledgebase</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Categories</h3>
                <div class="box-tools">
                    <a href="{{ route('admin.knowledgebase.category.new') }}"><button type="button" class="btn btn-sm btn-primary" style="border-radius: 0 3px 3px 0;margin-left:-1px;">Create New</button></a>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Information</th>
                            <th></th>
                        </tr>
                        @if(!$categories->isEmpty())
                            @foreach ($categories as $category)
                                <tr data-server="{{ $category->id }}">
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->description }}</td>
                                    <td class="text-center">
                                        <a class="btn btn-sm btn-danger pull-right" onclick="deleteMenuCategory({!! $category->id !!})">@csrf<i class="fa fa-trash-o"></i></a>
                                        <a class="btn btn-sm btn-primary pull-right" style="margin-right: 5%" href="{{ route('admin.knowledgebase.category.edit', $category->id) }}"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Topics</h3>
                <div class="box-tools">
                    <a href="{{ route('admin.knowledgebase.topics.new') }}"><button type="button" class="btn btn-sm btn-primary" style="border-radius: 0 3px 3px 0;margin-left:-1px;">Create New</button></a>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>Id</th>
                            <th>Category</th>
                            <th>Subject</th>
                            <th>Author</th>
                            <th>Last Updated</th>
                            <th></th>
                        </tr>
                        @if(!$topics->isEmpty())
                            @foreach ($topics as $topic)
                                <tr data-server="{{ $category->id }}">
                                    <td>{{ $topic->id }}</td>
                                    <td>{{ $topic->category }}</td>
                                    <td>{{ $topic->subject }}</td>
									<td>{{ $topic->author }}</td>
                                    <td>{{ $topic->updated_at }}</td>
                                    <td class="text-center">
                                        <a class="btn btn-sm btn-danger pull-right" onclick="deleteMenuQuestion({!! $topic->id !!})">@csrf<i class="fa fa-trash-o"></i></a>
                                        <a class="btn btn-sm btn-primary pull-right" style="margin-right: 5%" href="{{ route('admin.knowledgebase.topics.edit', $topic->id) }}"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        function showErrorDialog(jqXHR, verb) {
            console.error(jqXHR);
            let errorText = '';
            if (!jqXHR.responseJSON) {
                errorText = jqXHR.responseText;
            } else if (jqXHR.responseJSON.error) {
                errorText = jqXHR.responseJSON.error;
            } else if (jqXHR.responseJSON.errors) {
                $.each(jqXHR.responseJSON.errors, function (i, v) {
                    if (v.detail) {
                        errorText += v.detail + ' ';
                    }
                });
            }
            swal({
                title: 'Whoops!',
                text: 'An error occurred:' + errorText,
                type: 'error'
            });
        }

		function deleteMenuCategory(id){
			let urlCategory = `/admin/knowledgebase/category/delete/${id}`

			swal({
				title: 'Warning',
				type: 'warning',
				text: 'Are you sure that you want to delete this category? There is no going back, all data will immediately be removed.',
				showCancelButton: true,
				confirmButtonText: 'Delete',
				confirmButtonColor: '#d9534f',
				closeOnConfirm: true
			}, function () {
				$.ajax({
						method: 'POST',
						url: urlCategory,
						headers: { 'X-CSRF-Token': $('input[name="_token"]').val() }
					}).fail(function (jqXHR) {
						showErrorDialog(jqXHR, 'save');
					});
				location.reload();
			})
		}

		function deleteMenuQuestion(id){
			let urlQuestion = `/admin/knowledgebase/topics/delete/${id}`

			swal({
				title: 'Warning',
				type: 'warning',
				text: 'Are you sure that you want to delete this topic? There is no going back, all data will immediately be removed.',
				showCancelButton: true,
				confirmButtonText: 'Delete',
				confirmButtonColor: '#d9534f',
				closeOnConfirm: true
			}, function () {
				$.ajax({
						method: 'POST',
						url: urlQuestion,
						headers: { 'X-CSRF-Token': $('input[name="_token"]').val() }
					}).fail(function (jqXHR) {
						showErrorDialog(jqXHR, 'save');
					});
				location.reload();
			})
		}
    </script>
@endsection
