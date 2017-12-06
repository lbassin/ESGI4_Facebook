<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Foliobook</title>

        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css'), env('REDIRECT_HTTPS') }}">
    </head>
    <body>
        <div id="app">

        </div>

        <div id="particles-js"></div>
        <span class='pulse-button'>Créer mon site</span>

        <script>
            window.fbLoaded = function (){
                console.log('Fb ready');
            }
        </script>
        <script src="{{ asset('js/app.js'), env('REDIRECT_HTTPS') }}"></script>
        <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
        <script src="{{ asset('js/particles.js'), env('REDIRECT_HTTPS') }}"></script>

    </body>
</html>
