<!DOCTYPE html>
<html>
    <head>
        <title>{{ config('app.name', 'Pterodactyl') }}</title>

        @section('meta')
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <meta name="robots" content="noindex">
            <meta property="og:type" content="website" />
            <meta property="og:image" content="{{ $helionixConfiguration['meta_logo'] }}"/>
            <meta property="og:title" content="{{ $helionixConfiguration['meta_title'] }}">
            <meta property="og:description" content="{{ $helionixConfiguration['meta_description'] }}" />
            <meta name="theme-color" content="{{ $helionixConfiguration['meta_color'] }}">
            <link rel="apple-touch-icon" sizes="180x180" href="{{ $helionixConfiguration['favicon'] }}">
            <link rel="icon" type="image/png" href="{{ $helionixConfiguration['favicon'] }}" sizes="32x32">
            <link rel="icon" type="image/png" href="{{ $helionixConfiguration['favicon'] }}" sizes="16x16">
            <link rel="manifest" href="/favicons/manifest.json">
            <link rel="mask-icon" href="{{ $helionixConfiguration['favicon'] }}" color="#bc6e3c">
            <link rel="shortcut icon" href="{{ $helionixConfiguration['favicon'] }}">
            <meta name="msapplication-config" content="/favicons/browserconfig.xml">

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
                    --color-svg: {{ $helionixConfiguration['color_svg'] }};
                    --color-label: {{ $helionixConfiguration['color_label'] }};
                    --color-input: {{ $helionixConfiguration['color_input'] }};
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
                    
                    --alert-color-information: {{ $helionixConfiguration['alert_color_information'] }};
                    --alert-color-update: {{ $helionixConfiguration['alert_color_update'] }};
                    --alert-color-warning: {{ $helionixConfiguration['alert_color_warning'] }};
                    --alert-color-error: {{ $helionixConfiguration['alert_color_error'] }};
                }
            </style>
        @show

        @section('user-data')
            @if(!is_null(Auth::user()))
                <script>
                    window.PterodactylUser = {!! json_encode(Auth::user()->toVueObject()) !!};
                </script>
            @endif
            @if(!empty($siteConfiguration))
                <script>
                    window.SiteConfiguration = {!! json_encode($siteConfiguration) !!};
                </script>
            @endif
            @if(!empty($helionixConfiguration))
                <script>
                    window.HelionixConfiguration = {!! json_encode($helionixConfiguration) !!};
                </script>
            @endif
        @show

        <style>
            @import url('//fonts.googleapis.com/css?family=Rubik:300,4000,500&display=swap');
            @import url('//fonts.googleapis.com/css?family=IBM+Plex+Mono|IBM+Plex+Sans:500&display=swap');
            @import url('//cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css');
        </style>

        @yield('assets')

        @include('layouts.scripts')
        
        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='{{ config('app.tawkto', 'notprovided') }}';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
        </script>
        <!--End of Tawk.to Script-->
    </head>
    <body class="{{ $css['body'] ?? 'bg-neutral-50' }}">
        @section('content')
            @yield('above-container')
            @yield('container')
            @yield('below-container')
        @show
        @section('scripts')
            {!! $asset->js('main.js') !!}
        @show
    </body>
</html>
