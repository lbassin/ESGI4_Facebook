@extends('layouts/app')

@section('title', 'Support')

@section('header_scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/animejs/2.2.0/anime.min.js"></script>
@endsection

@section('content')
    <div id="home" style="height: 100px;">
        <nav>
            <div class="logo">
                Wawat
            </div>
            <a href="#">Pricing</a>
            <a href="#">Docs</a>
            <a href="#">Support</a>
            <a class="primary fb-login-open" href="#">Get started</a>
        </nav>
    </div>

    <div id="support">
        <h1>Support</h1>
        <form name="contact">
            <p>
                <label>
                    Nom <br>
                    <input type="text" name="name" required>
                </label>
            </p>
            <p>
                <label>
                    Email <br>
                    <input type="email" name="email" required>
                </label>
            </p>
            <p>
                <label>
                    Object <br>
                    <input type="text" name="subject" required>
                </label>
            </p>
            <p>
                <label>
                    Message <br>
                    <textarea name="content" id="" cols="30" rows="10" required></textarea>
                </label>
            </p>
            <button>Envoyer</button>
        </form>
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
@endsection

