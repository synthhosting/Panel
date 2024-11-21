<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ config('app.name', 'Pterodactyl') }} - @yield('title')</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="_token" content="{{ csrf_token() }}">

        <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
        <link rel="icon" type="image/png" href="/favicons/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="/favicons/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="/favicons/manifest.json">
        <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#bc6e3c">
        <link rel="shortcut icon" href="/favicons/favicon.ico">
        <meta name="msapplication-config" content="/favicons/browserconfig.xml">
        <meta name="theme-color" content="#0e4688">

        <style>
            :root{
                --color-1: {{ $helionixConfiguration['color_1'] }};
                --color-2: {{ $helionixConfiguration['color_2'] }};
                --color-3: {{ $helionixConfiguration['color_3'] }};
                --color-4: {{ $helionixConfiguration['color_4'] }};
                --color-5: {{ $helionixConfiguration['color_5'] }};
                --color-6: {{ $helionixConfiguration['color_6'] }};
                --color-console: {{ $helionixConfiguration['color_console'] }};
                --color-editor: {{ $helionixConfiguration['color_editor'] }};
                --color-h1: {{ $helionixConfiguration['color_h1'] }};
                --color-label: {{ $helionixConfiguration['color_label'] }};
                --color-p: {{ $helionixConfiguration['color_p'] }};
                --color-a: {{ $helionixConfiguration['color_a'] }};
                --color-span: {{ $helionixConfiguration['color_span'] }};
                --color-code: {{ $helionixConfiguration['color_code'] }};
                --color-strong: {{ $helionixConfiguration['color_strong'] }};
                --color-invalid: {{ $helionixConfiguration['color_invalid'] }};

                --button-primary: {{ $helionixConfiguration['button_primary'] }};
                --button-primary-hover: {{ $helionixConfiguration['button_primary_hover'] }};
                --button-secondary: {{ $helionixConfiguration['button_secondary'] }};
                --button-secondary-hover: {{ $helionixConfiguration['button_secondary_hover'] }};
                --button-danger: {{ $helionixConfiguration['button_danger'] }};
                --button-danger-hover: {{ $helionixConfiguration['button_danger_hover'] }};
            }
        </style>

        @include('layouts.scripts')

        @section('scripts')
        {!! Theme::css('vendor/select2/select2.min.css?t={cache-version}') !!}
        {!! Theme::css('vendor/bootstrap/bootstrap.min.css?t={cache-version}') !!}
        {!! Theme::css('vendor/adminlte/admin.min.css?t={cache-version}') !!}
        {!! Theme::css('vendor/adminlte/colors/skin-blue.min.css?t={cache-version}') !!}
        {!! Theme::css('vendor/sweetalert/sweetalert.min.css?t={cache-version}') !!}
        {!! Theme::css('vendor/animate/animate.min.css?t={cache-version}') !!}
        {!! Theme::css('css/helionix.css?t={cache-version}') !!}

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        @show
    </head>
    <body>
        <div class="container">
            <div class="sidebar" id="sidebar">
                <li class="back-btn">
                    <a href="{{ route('admin.index') }}">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </li>
                <li class="support-btn">
                    <a href="https://discord.gg/rBuseTnRBq" target="_blank">
                        <i class="fab fa-discord"></i>
                    </a>
                </li>
                <li class="{{ Route::currentRouteName() !== 'admin.helionix.general' ?: 'active' }}">
                    <a href="{{ route('admin.helionix.general') }}">
                        <i class="fas fa-magic"></i>
                    </a>
                </li>
                <li class="{{ Route::currentRouteName() !== 'admin.helionix.meta' ?: 'active' }}">
                    <a href="{{ route('admin.helionix.meta') }}">
                        <i class="fas fa-tag"></i>
                    </a>
                </li>
                <li class="{{ Route::currentRouteName() !== 'admin.helionix.color' ?: 'active' }}">
                    <a href="{{ route('admin.helionix.color') }}">
                        <i class="fas fa-palette"></i>
                    </a>
                </li>
                <li class="{{ ! starts_with(Route::currentRouteName(), 'admin.helionix.alert') ?: 'active' }}">
                    <a href="{{ route('admin.helionix.alert') }}">
                        <i class="fas fa-exclamation-triangle"></i>
                    </a>
                </li>
                <li class="{{ ! starts_with(Route::currentRouteName(), 'admin.helionix.announcement') ?: 'active' }}">
                    <a href="{{ route('admin.helionix.announcement') }}">
                        <i class="fas fa-bell"></i>
                    </a>
                </li>
                <li class="{{ Route::currentRouteName() !== 'admin.helionix.dashboard' ?: 'active' }}">
                    <a href="{{ route('admin.helionix.dashboard') }}">
                        <i class="fas fa-layer-group"></i>
                    </a>
                </li>
                <li class="{{ Route::currentRouteName() !== 'admin.helionix.uptime' ?: 'active' }}">
                    <a href="{{ route('admin.helionix.uptime') }}">
                        <i class="fas fa-rocket"></i>
                    </a>
                </li>
                <li class="{{ Route::currentRouteName() !== 'admin.helionix.server' ?: 'active' }}">
                    <a href="{{ route('admin.helionix.server') }}">
                        <i class="fas fa-server"></i>
                    </a>
                </li>
                <li class="{{ Route::currentRouteName() !== 'admin.helionix.authentication' ?: 'active' }}">
                    <a href="{{ route('admin.helionix.authentication') }}">
                        <i class="fas fa-shield-alt"></i>
                    </a>
                </li>
                @yield('button-save')
            </div>
            <div class="content">
                <div class="content-editor">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            There was an error validating the data provided.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @foreach (Alert::getMessages() as $type => $messages)
                        @foreach ($messages as $message)
                            <div class="alert alert-{{ $type }} alert-dismissable" role="alert">
                                {!! $message !!}
                            </div>
                        @endforeach
                    @endforeach
                    <section class="content-body">
                        @yield('content')
                    </section>
                </div>
                <div class="content-show">
                    @yield('content-show')
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.js"></script>
        <script>
            Coloris({
                themeMode: 'light',
                alpha: false,
                format: 'hex',
                swatches: [
                    '#ff6666',
                    '#ffc466',
                    '#ccff66',
                    '#69ff66',
                    '#66ffa1',
                    '#66ffd6',
                    '#66e6ff',
                    '#66b0ff',
                    '#6678ff',
                    '#9466ff',
                    '#cc66ff',
                    '#ff66d9'
                ],
            });
        </script>
    </body>
</html>