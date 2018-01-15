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
            @include('home.header')

            <div class="content">
                <div class="text">
                    <div>
                        <p>Construisez un site web qui vous correspond</p>
                    </div>
                    <button class="fb-login-open">Créez votre site</button>
                </div>
                <div class="image">
                    <img src="{{ asset('images/home.jpg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
    @include('home.login-modal')

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

        $('.fb-login-close').click(closeModal);
        $('.fb-login-open').click(openModal);

        function openModal(){
            $('.login-modal').fadeIn();
            $('.login-modal-overlay').fadeIn();
        }

        function closeModal(){
            $('.login-modal').fadeOut();
            $('.login-modal-overlay').fadeOut();
        }
    </script>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/fb-login.js') }}"></script>
    <script src="{{ asset('js/particles.js') }}"></script>
@endsection

