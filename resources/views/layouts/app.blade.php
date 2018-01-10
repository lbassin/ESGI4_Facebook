<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('header_scripts')
</head>
<body>
<?php
/** @var \App\Http\Helpers\FacebookHelper $fbHelper */
$fbHelper = resolve('App\Http\Helpers\FacebookHelper');
/** @var \App\Http\Helpers\WebsiteHelper $websiteHelper */
$websiteHelper = resolve('App\Http\Helpers\WebsiteHelper');
?>
<script>
    window.fbData = {
        appId: '{{ env('FACEBOOK_APP_ID') }}',
        scope: '{{ $fbHelper->getScopes() }}'
    };

    window.URLs = {
        dashboard: '{{ route('dashboard') }}',
        websiteAdmin: '{{ route('dashboard.website', ['subdomain' => $websiteHelper->getCurrentWebsite()->getSubDomain()]) }}',
        albums: {
            create: '{{ route('dashboard.website.albums.create', ['subdomain' => $websiteHelper->getCurrentWebsite()->getSubDomain() ]) }}'
        }
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<div id="flash-messages">
    <div class="message success show">
        <i class="fa fa-check" aria-hidden="true"></i> L'album a été correctement créé
    </div>
</div>

<script>
    setTimeout(function(){
        $('#flash-messages .success').removeClass('show');
    }, 3500);
</script>

@yield('content')

</body>
</html>
