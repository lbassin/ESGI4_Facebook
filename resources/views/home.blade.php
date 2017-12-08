@extends('layouts/app')

@section('title', 'Foliobook')

@section('content')
    <div id="app"></div>
    <div id="particles-js"></div>

    <span id="fbConnect" class="pulse-button">Créer mon site</span>

    <script src="{{ asset('js/fb-login.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/particles.js') }}"></script>
@endsection