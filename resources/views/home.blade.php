@extends('layouts/app')

@section('title', 'Accueil')

@section('header_scripts')
    <script src="//cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/animejs/2.2.0/anime.min.js"></script>
@endsection

@section('content')
    <div class="alternative-background"></div>
    <div class="home-header">
        <div class="background">
            <div id="particles-js"></div>
        </div>
        <div id="home">
            <nav>
                <div class="logo">
                    Wawat
                </div>
                <a href="#">Pricing</a>
                <a href="#">Docs</a>
                <a href="#">Support</a>
                <a class="primary" href="#">Get started</a>
            </nav>

            <div class="content">
                <div class="text">
                    <div>
                        <p>Construisez un site web qui vous correspond</p>
                    </div>
                    <button>Créez votre site</button>
                </div>
                <div class="image">
                    lorem
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            let logo = $('#home').find('.logo');
            logo.hide();

            logo.each(function () {
                $(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='letter'>$&</span>"));
            });

            setTimeout(function () {
                logo.show();
                anime.timeline({
                    loop: false
                }).add({
                    targets: '#home .logo .letter',
                    opacity: [0, 1],
                    scale: [0, 1],
                    duration: 1500,
                    elasticity: 600,
                    delay: function delay(el, i) {
                        return 90 * (i + 1);
                    }
                });
            }, 1500);
        });
    </script>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/fb-login.js') }}"></script>
    <script src="{{ asset('js/particles.js') }}"></script>
@endsection

