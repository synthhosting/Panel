@extends('layouts.helionix')

@section('title')
    Helionix Announcement
@endsection

@section('content')
    <h3>Announcement Settings</h3><p>Configure Announcement Helionix Theme.</p>
    <form action="{{ route('admin.helionix.announcement.create') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title" class="control-label">Title</label>
            <div>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" />
                <p class="text-muted"><small>The title for announcement.</small></p>
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="control-label">Description</label>
            <div>
                <textarea rows="4" id="description" name="description" class="form-control">{{ old('description') }}</textarea>
                <p class="text-muted"><small>The description for announcement.</small></p>
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-content">Create</button>
        </div>
    </form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.3.0/tinymce.min.js" integrity="sha512-RUZ2d69UiTI+LdjfDCxqJh5HfjmOcouct56utQNVRjr90Ea8uHQa+gCxvxDTC9fFvIGP+t4TDDJWNTRV48tBpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        tinymce.init({
            selector: 'textarea#description',
            height: 700,
            plugins:[
                'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media', 
                'table', 'emoticons', 'template', 'codesample'
            ],
            toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify |' + 
            'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
            'forecolor backcolor emoticons',
            menubar: 'favs edit view insert format tools table',
            content_style: '/assets/css/styles.css',
            skin: "oxide-dark",
            content_css: "dark"
        });
    </script>
@endsection

@section('content-show')
    @if($announcements_status)
        <iframe src="/announcement" frameborder="0"></iframe>
    @else
        <div class="no-preview">No preview available</div>
    @endif
@endsection