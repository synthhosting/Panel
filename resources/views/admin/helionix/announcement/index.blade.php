@extends('layouts.helionix')

@section('title')
    Helionix Announcement
@endsection

@section('content')
    <h3>Announcement Settings</h3><p>Configure Announcement Helionix Theme.</p>
    <form action="{{ route('admin.helionix.announcement') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="helionix:announcements_status" class="control-label">Announcement Status</label>
            <select class="form-control" name="helionix:announcements_status" value="{{ old('helionix:announcements_status', $announcements_status) }}">
                <option value="1">Enable</option>
                <option value="0" @if(old('helionix:announcements_status', $announcements_status) == '0') selected @endif>Disable</option>
            </select>
            <p class="text-muted"><small>Enable or disable announcements page.</small></p>
        </div>
        @section('button-save')
        <li class="save-btn">
            <a onclick="document.querySelector('form').submit();">
                <i class="fas fa-save"></i>
            </a>
        </li>
        @endsection
    </form>
    <div class="form-group">
        <a href="/admin/helionix/announcement/new" class="btn btn-content">Create new</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Title</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($announcements as $announcement)
                <tr>
                    <td>{{ $announcement->title }}</td>
                    <td class="action">
                        <a href="/admin/helionix/announcement/edit/{{ $announcement->id }}">
                            <i class="fas fa-edit btn-edit"></i>
                        </a>
                        <form action="{{ route('admin.helionix.announcement.delete', $announcement->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <a href="javascript:void(0)" onclick="this.closest('form').submit();">
                                <i class="fas fa-trash btn-delete"></i>
                            </a>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('content-show')
    @if($announcements_status)
        <iframe src="/announcement" frameborder="0"></iframe>
    @else
        <div class="no-preview">No preview available</div>
    @endif
@endsection