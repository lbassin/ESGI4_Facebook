@extends('layouts.app')

@section('title', 'Facebook Permissions')

@section('header_scripts')
    <script src="//cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
@endsection

@section('content')
    <div class="wrapper">
        <div id="particles-js"></div>

        <h1 class="title-permission">Des permissions sont manquantes</h1>

        <button class="spin" id="reAskPermissions">
            <span>Autoriser l'application</span>
            <span>
                    <svg viewBox="0 0 24 24">
                        <path d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"/>
                    </svg>
                </span>
        </button>

    </div>

    <script>
        let redirectTo = '{{ $redirectTo }}';
    </script>
    <script src="{{ asset('js/fb-reAskPermissions.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/particles.js') }}"></script>
@endsection