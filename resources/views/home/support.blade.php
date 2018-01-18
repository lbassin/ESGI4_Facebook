@extends('layouts/app')

@section('title', 'Support')

@section('header_scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/animejs/2.2.0/anime.min.js"></script>
@endsection

@section('content')
    <div id="home" style="height: 100px;">
        @include('home.header')
    </div>

    <div id="support">
        <h1>Support</h1>
        <form name="contact" method="POST" class="m-form">
            <fieldset>
                <legend>Fill this form to contact the support</legend>

                <div class="input text-block">
                    <input type="text" name="name" required>
                    <label>Nom</label>
                </div>
                <div class="input text-block">
                    <input type="email" name="email" value="" onkeyup="this.setAttribute('value', this.value);" required>
                    <label>Email</label>
                </div>
                <div class="input text-block">
                    <input type="text" name="subject" required>
                    <label>Object</label>
                </div>
                <div class="input textarea-block">
                    <textarea name="content" id="" cols="30" rows="10" required></textarea>
                    <label>Message</label>
                </div>
                <button>Envoyer</button>
            </fieldset>
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

