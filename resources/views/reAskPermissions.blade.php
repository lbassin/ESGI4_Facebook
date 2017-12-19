@extends('layouts/app')

@section('title', 'Facebook Permissions')

@section('content')
    <h1>Permissions</h1>
    <p>
        Des permissions sont manquantes [...]
    </p>
    <button id="reAskPermissions">Allow the application</button>

    <script>
        let redirectTo = '{{ $redirectTo }}';
    </script>
    <script src="{{ asset('js/fb-reAskPermissions.js') }}"></script>
@endsection