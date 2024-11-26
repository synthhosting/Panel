@extends('layouts.admin')

@section('title')
    Wings Updater
@endsection

@section('content-header')
    <h1>Wings Updater<small>Update wings on all nodes</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Admin</a></li>
        <li>Velta Studios</li>
        <li class="active">Wings Updater</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-refresh"></i> Update Wings</h3>
                </div>
                <div class="box-body">
                    <form id="update-wings-form" class="form-horizontal text-center">
                        @csrf
                        <div class="form-group">
                            <label for="update_type" class="col-sm-2 control-label">Update Type:</label>
                            <div class="col-sm-10">
                                <select name="update_type" id="update_type" class="form-control" required>
                                    <option value="default">Default Wings</option>
                                    <option value="custom">Custom Wings</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="custom-wings-group" style="display: none;">
                            <label for="download_link" class="col-sm-2 control-label">Download Link:</label>
                            <div class="col-sm-10">
                                <input type="text" name="download_link" id="download_link" class="form-control" placeholder="Enter custom download link">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-cloud-upload"></i> Update Wings for All Nodes</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div id="update-results" class="text-center" style="margin-top: 20px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .box-title {
            display: flex;
            align-items: center;
        }
        .form-horizontal .form-group {
            margin-right: 10px;
        }
        .text-center {
            text-align: center;
        }
        .log-info {
            color: lightblue;
        }
        .log-success {
            color: lightgreen;
        }
        .log-error {
            color: lightcoral;
        }
        .box-body hr {
            border-top: 1px solid #ddd;
        }
    </style>
@endsection

@section('footer-scripts')
    @parent
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var updateType = document.getElementById('update_type');
            var customWingsGroup = document.getElementById('custom-wings-group');
            var updateResults = document.getElementById('update-results');
            var updateForm = document.getElementById('update-wings-form');

            function toggleCustomWingsGroup() {
                if (updateType.value === 'custom') {
                    customWingsGroup.style.display = 'block';
                } else {
                    customWingsGroup.style.display = 'none';
                }
            }

            updateType.addEventListener('change', toggleCustomWingsGroup);
            toggleCustomWingsGroup();

            function appendLog(message, type) {
                var logMessage = document.createElement('pre');
                logMessage.textContent = message;
                logMessage.classList.add('log-' + type);
                updateResults.appendChild(logMessage);
            }

            updateForm.addEventListener('submit', function(event) {
                event.preventDefault();
                updateResults.innerHTML = '';
                appendLog('Starting the update process...', 'info');
                var formData = new FormData(updateForm);
                var nodes = @json($nodes);

                function updateNode(index) {
                    if (index >= nodes.length) {
                        appendLog('All updates complete.', 'success');
                        return;
                    }

                    var node = nodes[index];
                    fetch(`{{ url('admin/veltastudios/nodes/wings-updater/update') }}/${node.id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            update_type: formData.get('update_type'),
                            download_link: formData.get('download_link')
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        appendLog(data.result, 'info');
                        updateNode(index + 1);
                    })
                    .catch(error => {
                        appendLog(`Error updating ${node.name}: ${error.message}`, 'error');
                        updateNode(index + 1);
                    });
                }

                updateNode(0);
            });
        });
    </script>
@endsection
