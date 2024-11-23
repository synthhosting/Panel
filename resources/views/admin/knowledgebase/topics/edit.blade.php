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
    <form action="{{ route('admin.knowledgebase.topics.update', $topic->id) }}" method="POST">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Topic Editor</h3>
                </div>
                <div class="box-body">
					<div class="form-group">
                        <label for="subject" class="control-label">Subject</label>
                        <div>
                            <input type="text" name="subject" class="form-control" value="{{ $topic->subject }}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="author" class="control-label">Created By</label>
                        <div>
                            <input type="text" name="author" class="form-control" value="{{ $topic->author }}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="category" class="form-label">Category</label>
						<select name="category" class="form-control">
						    @foreach($categories as $category)
                                @if($topic->category == $category->id)
                                    <option selected value="{{ $category->id }}">{{ $category->name }}</option>
                                @else
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endif
						    @endforeach
						</select>
                    </div>
                    <div class="form-group">
                        <label for="answer" class="form-label">Answer</label>
						<textarea name="answer" id="answer" rows="10" class="form-control">{{ $topic->information }}</textarea>
                    </div>
                    <div class="box-footer">
                        {!! csrf_field() !!}
                        <button type="submit" class="btn btn-primary pull-right">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
		CKEDITOR.replace('answer');
    </script>
@endsection
