<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Foliobook</title>
    </head>
    <body>
        <div id="app"></div>
        <button id="fbConnect">Connexion FB</button>

        <script>
            window.fbAppId = '{{ env('FACEBOOK_APP_ID') }}';
        </script>

        <script src="{{ asset('js/fb-login.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
