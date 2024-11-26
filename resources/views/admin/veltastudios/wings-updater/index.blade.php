@extends('layouts.admin')

@section('title')
    Wings Updater
@endsection

@section('content-header')
    <h1><i class="fa fa-upload"></i> Wings Updater <small>Update wings on all nodes</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><i class="fa fa-cogs"></i> Velta Studios</li>
        <li class="active"><i class="fa fa-upload"></i> Wings Updater</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div id="version-alert" class="alert alert-warning" style="display: none;">
                <i class="fa fa-exclamation-triangle"></i> You are not using the latest version of the addon. Please update to the latest version.
            </div>
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fa fa-check"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    <i class="fa fa-times"></i> {{ session('error') }}
                </div>
            @endif

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-upload"></i> Wings Updater</h3>
                    <div class="box-tools pull-right">
                        <button id="test-connections" class="btn btn-warning btn-sm">
                            <i class="fa fa-wifi"></i> Test Connections
                        </button>
                        <a href="{{ route('admin.veltastudios.nodes.wings-updater.showUpdatePage') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-refresh"></i> Update Wings
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted">
                                <strong><i class="fa fa-info-circle"></i> Note:</strong> This will update wings on all nodes using the configured SSH credentials. Ensure that your nodes are accessible and configured correctly.
                            </p>
                        </div>
                        <div class="col-md-6 text-right">
                            <p>
                                <strong><i class="fa fa-code-fork"></i> Addon Version:</strong> <span id="current-addon-version">1.0.2</span> | 
                                <strong><i class="fa fa-cloud"></i> Latest Wings Version:</strong> <span id="current-wings-version">Fetching...</span> | 
                                <strong><i class="fa fa-support"></i> Support:</strong> <a href="https://discord.gg/adNXC8VeSv" target="_blank">Discord</a>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div id="connection-results" class="test-results"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Section -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-info-circle"></i> Information</h3>
                </div>
                <div class="box-body">
                    <p>
                        <strong><i class="fa fa-key"></i> Configuration Methods:</strong><br>
                        <strong><i class="fa fa-lock"></i> Global Private Key:</strong> Use a single private key for all nodes.<br>
                        <strong><i class="fa fa-unlock"></i> Global Password:</strong> Use a single password for all nodes.<br>
                        <strong><i class="fa fa-server"></i> Individual Node Configuration:</strong> Each node can have its own private key or password.
                    </p>
                    <p>
                        <strong><i class="fa fa-cogs"></i> Wings Modes:</strong><br>
                        <strong><i class="fa fa-check"></i> Default:</strong> Use the default wings version provided by the system.<br>
                        <strong><i class="fa fa-wrench"></i> Custom:</strong> Use a custom wings version specified by you.
                    </p>
                </div>
            </div>

            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-cog"></i> Configure Nodes</h3>
                    <div class="box-tools pull-right">
                        <button id="edit-configuration" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Edit Configuration</button>
                    </div>
                </div>
                <div class="box-body">
                    @if ($globalConfig || $nodeConfigs->isNotEmpty())
                        <div id="current-configuration">
                            <p><i class="fa fa-cog"></i> Current Configuration: <strong>{{ $globalConfig ? 'Global' : 'Individual Nodes' }}</strong></p>
                            <p><i class="fa fa-cog"></i> Wings Mode: <strong>{{ $globalConfig->wings_mode ?? 'N/A' }}</strong></p>
                        </div>
                    @endif

                    <form id="configuration-form" action="{{ route('admin.veltastudios.nodes.wings-updater.saveConfiguration') }}" method="POST" style="display: {{ $globalConfig || $nodeConfigs->isNotEmpty() ? 'none' : 'block' }};">
                        @csrf
                        <div class="form-group">
                            <label for="config_type"><i class="fa fa-cogs"></i> Configuration Type</label>
                            <select name="config_type" id="config_type" class="form-control" required>
                                <option value="global" {{ $globalConfig ? 'selected' : '' }}>Global Configuration</option>
                                <option value="individual" {{ $nodeConfigs->isNotEmpty() ? 'selected' : '' }}>Individual Node Configuration</option>
                            </select>
                        </div>
                        <div id="global-config" style="display: {{ $globalConfig ? 'block' : 'none' }};">
                            <div class="form-group">
                                <label for="method"><i class="fa fa-key"></i> Configuration Method</label>
                                <select name="method" id="method" class="form-control" required>
                                    <option value="global_private_key" {{ $globalConfig && $globalConfig->method === 'global_private_key' ? 'selected' : '' }}>Global Private Key</option>
                                    <option value="global_password" {{ $globalConfig && $globalConfig->method === 'global_password' ? 'selected' : '' }}>Global Password</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="wings_mode"><i class="fa fa-cog"></i> Wings Mode</label>
                                <select name="wings_mode" id="wings_mode" class="form-control" required>
                                    <option value="default" {{ $globalConfig && $globalConfig->wings_mode === 'default' ? 'selected' : '' }}>Default Wings</option>
                                    <option value="custom" {{ $globalConfig && $globalConfig->wings_mode === 'custom' ? 'selected' : '' }}>Custom Wings</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="credential"><i class="fa fa-key"></i> Credential</label>
                                <textarea name="credential" id="credential" class="form-control" rows="4" required>{{ $globalConfig ? $globalConfig->credential : '' }}</textarea>
                            </div>
                            <div class="form-group" id="passphrase-group" style="display: {{ $globalConfig && $globalConfig->method === 'global_private_key' ? 'block' : 'none' }};">
                                <label for="passphrase"><i class="fa fa-key"></i> Passphrase (if applicable)</label>
                                <input type="text" name="passphrase" id="passphrase" class="form-control" value="{{ $globalConfig && $globalConfig->passphrase ? $globalConfig->passphrase : '' }}">
                            </div>
                            <div class="form-group">
                                <label for="ssh_user"><i class="fa fa-user"></i> SSH User</label>
                                <input type="text" name="ssh_user" id="ssh_user" class="form-control" value="{{ $globalConfig ? $globalConfig->ssh_user : 'root' }}" required>
                            </div>
                            <div class="form-group">
                                <label for="ssh_port"><i class="fa fa-plug"></i> SSH Port</label>
                                <input type="number" name="ssh_port" id="ssh_port" class="form-control" value="{{ $globalConfig ? $globalConfig->ssh_port : 22 }}" required>
                            </div>
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Configuration</button>
                        </div>
                    </form>

                    <form id="individual-configuration-form" action="{{ route('admin.veltastudios.nodes.wings-updater.saveConfiguration') }}" method="POST" style="display: {{ $nodeConfigs->isNotEmpty() ? 'block' : 'none' }};">
                        @csrf
                        <input type="hidden" name="config_type" value="individual">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><i class="fa fa-server"></i> Node Name</th>
                                    <th><i class="fa fa-key"></i> Configuration Method</th>
                                    <th><i class="fa fa-cog"></i> Wings Mode</th>
                                    <th><i class="fa fa-key"></i> Credential</th>
                                    <th><i class="fa fa-key"></i> Passphrase (if applicable)</th>
                                    <th><i class="fa fa-user"></i> SSH User</th>
                                    <th><i class="fa fa-plug"></i> SSH Port</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nodes as $node)
                                    <tr>
                                        <td>{{ $node->name }}</td>
                                        <td>
                                            <select name="nodes[{{ $node->id }}][method]" class="form-control" required>
                                                <option value="node_private_key" {{ $nodeConfigs->where('node_id', $node->id)->first() && $nodeConfigs->where('node_id', $node->id)->first()->method === 'node_private_key' ? 'selected' : '' }}>Private Key</option>
                                                <option value="node_password" {{ $nodeConfigs->where('node_id', $node->id)->first() && $nodeConfigs->where('node_id', $node->id)->first()->method === 'node_password' ? 'selected' : '' }}>Password</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="nodes[{{ $node->id }}][wings_mode]" class="form-control" required>
                                                <option value="default" {{ $nodeConfigs->where('node_id', $node->id)->first() && $nodeConfigs->where('node_id', $node->id)->first()->wings_mode === 'default' ? 'selected' : '' }}>Default Wings</option>
                                                <option value="custom" {{ $nodeConfigs->where('node_id', $node->id)->first() && $nodeConfigs->where('node_id', $node->id)->first()->wings_mode === 'custom' ? 'selected' : '' }}>Custom Wings</option>
                                            </select>
                                        </td>
                                        <td>
                                            <textarea name="nodes[{{ $node->id }}][credential]" class="form-control" rows="2" required>{{ $nodeConfigs->where('node_id', $node->id)->first() ? $nodeConfigs->where('node_id', $node->id)->first()->credential : '' }}</textarea>
                                        </td>
                                        <td>
                                            <input type="text" name="nodes[{{ $node->id }}][passphrase]" class="form-control" value="{{ $nodeConfigs->where('node_id', $node->id)->first() && $nodeConfigs->where('node_id', $node->id)->first()->passphrase ? $nodeConfigs->where('node_id', $node->id)->first()->passphrase : '' }}">
                                        </td>
                                        <td>
                                            <input type="text" name="nodes[{{ $node->id }}][ssh_user]" class="form-control" value="{{ $nodeConfigs->where('node_id', $node->id)->first() ? $nodeConfigs->where('node_id', $node->id)->first()->ssh_user : 'root' }}" required>
                                        </td>
                                        <td>
                                            <input type="number" name="nodes[{{ $node->id }}][ssh_port]" class="form-control" value="{{ $nodeConfigs->where('node_id', $node->id)->first() ? $nodeConfigs->where('node_id', $node->id)->first()->ssh_port : 22 }}" required>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Configuration</button>
                    </form>
                </div>
            </div>
            
            @if($nodesOutdated->isNotEmpty())
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-exclamation-triangle"></i> Nodes Outdated</h3>
                </div>
                <div class="box-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fa fa-server"></i> Node Name</th>
                                <th><i class="fa fa-code-fork"></i> Current Version</th>
                                <th><i class="fa fa-cloud-upload"></i> Latest Version</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nodesOutdated as $node)
                                <tr>
                                    <td>{{ $node['name'] }}</td>
                                    <td>{{ $node['current_version'] }}</td>
                                    <td>{{ $node['latest_version'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>
    <footer class="text-center">
        <p>Developed by <a href="https://discord.gg/adNXC8VeSv" target="_blank">Velta Studios</a></p>
    </footer>
@endsection

@section('styles')
    <style>
        .box-title {
            display: flex;
            align-items: center;
        }
        .box-title i {
            margin-right: 10px;
        }
        .box-tools .btn {
            margin-left: 10px;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .table-hover > tbody > tr:hover {
            background-color: #f5f5f5;
        }
        .alert {
            margin-top: 20px;
        }
        .text-center.text-muted {
            margin-top: 20px;
            font-size: 1.1em;
        }
        .test-results pre {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
@endsection

@section('footer-scripts')
    @parent
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentAddonVersion = '1.0.2';

            fetch('{{ route('admin.veltastudios.nodes.wings-updater.wingsVersion') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.version) {
                        document.getElementById('current-wings-version').innerText = data.version;
                    } else {
                        console.error('Error fetching wings version:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error fetching wings version:', error);
                });

            fetch('{{ route('admin.veltastudios.nodes.wings-updater.version') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.version && data.version !== currentAddonVersion) {
                        document.getElementById('version-alert').style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error fetching addon version:', error);
                });

            var globalConfig = document.getElementById('global-config');
            var individualConfig = document.getElementById('individual-configuration-form');
            var configType = document.getElementById('config_type');
            var method = document.getElementById('method');
            var passphraseGroup = document.getElementById('passphrase-group');
            var editButton = document.getElementById('edit-configuration');
            var currentConfig = document.getElementById('current-configuration');
            var configForm = document.getElementById('configuration-form');

            function toggleConfigType() {
                if (configType.value === 'global') {
                    globalConfig.style.display = 'block';
                    individualConfig.style.display = 'none';
                } else {
                    globalConfig.style.display = 'none';
                    individualConfig.style.display = 'block';
                }
            }

            function togglePassphraseGroup() {
                if (method.value === 'global_private_key') {
                    passphraseGroup.style.display = 'block';
                } else {
                    passphraseGroup.style.display = 'none';
                }
            }

            if (editButton) {
                editButton.addEventListener('click', function() {
                    configForm.style.display = 'block';
                    currentConfig.style.display = 'none';
                });
            }

            configType.addEventListener('change', toggleConfigType);
            method.addEventListener('change', togglePassphraseGroup);

            toggleConfigType();
            togglePassphraseGroup();
        });

        document.getElementById('test-connections').addEventListener('click', function() {
            var nodes = @json($nodes);
            var resultsDiv = document.getElementById('connection-results');
            resultsDiv.innerHTML = '';

            function testConnection(index) {
                if (index >= nodes.length) {
                    var completeMessage = document.createElement('pre');
                    completeMessage.innerText = 'Test connections complete.';
                    resultsDiv.appendChild(completeMessage);
                    return;
                }

                var node = nodes[index];
                var resultPre = document.createElement('pre');
                resultPre.innerText = `Attempting to connect to ${node.name} (${node.fqdn})...`;
                resultsDiv.appendChild(resultPre);

                fetch(`{{ url('admin/veltastudios/nodes/wings-updater/test') }}/${node.id}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        resultPre.innerText += `\n${data.result}`;
                        testConnection(index + 1);
                    })
                    .catch(error => {
                        resultPre.innerText += `\nConnection failed: ${error.message}`;
                        testConnection(index + 1);
                    });
            }

            testConnection(0);
        });
    </script>
@endsection
