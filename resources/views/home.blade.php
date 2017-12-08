@extends('layouts/app')

@section('title', 'Foliobook')

@section('header_scripts')
    <script src="//cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/animejs/2.2.0/anime.min.js"></script>
@endsection

@section('content')
    <div class="wrapper">
        <div id="particles-js"></div>

        <h1 class="title">FolioBook</h1>

        <button class="spin" id="spin">
            <span>Créer mon site</span>
            <span>
                    <svg viewBox="0 0 24 24">
                        <path d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"/>
                    </svg>
                </span>
        </button>

        <div class="item">
            <div class="mouse m-4"></div>
        </div>
    </div>

    <div style="height: 500px;">
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/fb-login.js') }}"></script>
    <script src="{{ asset('js/particles.js') }}"></script>
@endsection