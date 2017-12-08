<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Foliobook</title>

        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
        <script src="//cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    </head>
    <body>
        <div id="app"></div>
        <div id="particles-js"></div>

        <span id="fbConnect" class="pulse-button">Créer mon site</span>

        <script>
            window.fbData = {
                appId: '{{ env('FACEBOOK_APP_ID') }}',
                scope: 'public_profile,email'
            };

            window.URLs = {
                dashboard: '{{ route('dashboard') }}'
            }
        </script>

        <script src="{{ asset('js/fb-login.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/particles.js') }}"></script>
    </body>
</html>
