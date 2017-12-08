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
        <script src="//cdnjs.cloudflare.com/ajax/libs/animejs/2.2.0/anime.min.js"></script>
    </head>
    <body>
        <div id="app"></div>

        <div class="wrapper">
            <div id="particles-js"></div>

            <h1 class="title">FolioBook</h1>

            <button class="spin" id="spin">
                <span>Créer mon site</span>
                <span>
                    <svg viewBox="0 0 24 24">
                        <path d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" />
                    </svg>
                </span>
            </button>

            <div class="item">
                <div class="mouse m-4"></div>
            </div>
        </div>

        <div style="height: 500px;">

        </div>

        <script>
            window.fbLoaded = function (){
                console.log('Fb ready');
            }
        </script>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/fb-login.js') }}"></script>
        <script src="{{ asset('js/particles.js') }}"></script>

        <script>
            window.fbData = {
                appId: '{{ env('FACEBOOK_APP_ID') }}',
                scope: 'public_profile,email'
            };

            window.URLs = {
                dashboard: '{{ route('dashboard') }}'
            }
        </script>
    </body>
</html>