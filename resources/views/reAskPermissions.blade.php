@extends('layouts/app')

@section('title', 'Facebook Permissions')

@section('content')
    <h1>Permissions</h1>
    <p>
        Des permissions sont manquantes [...]
    </p>
    <button>Allow the application</button>

    <script src="{{ asset('js/fb-reAskPermission.js') }}"></script>
@endsection