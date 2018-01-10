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

<script>
    function addSuccess(message) {
        addMessage(message, 'check', 'success');
    }

    function addError(message) {
        addMessage(message, 'times', 'error');
    }

    function addMessage(message, icon, style) {
        let wrapper = $('#flash-messages');

        let success = $('<div>').addClass('message');
        let span = $('<span>').addClass(style);

        let prefix = $('<i>').addClass('fa').addClass('fa-' + icon).attr('aria-hidden', 'true');
        span.append(prefix);
        span.append(' ' + message);

        success.append(span);
        wrapper.append(success);

        setTimeout(function () {
            span.addClass('show');
        }, 100);

        setTimeout(function () {
            span.removeClass('show');
            setTimeout(function () {
                span.parent().remove();
            }, 550);
        }, 3500);
    }

    function errorAjax() {
        addError('An error occurred');
    }
</script>

<div id="flash-messages">

</div>

@yield('content')

<script> // Modal
    function showModal(target) {
        $('#' + target).addClass('md-show');
    }

    $(document).on('keydown', function (event) {
        if (event.keyCode === 27) {
            $('.md-close').trigger('click');
        }
    });

    function hideModal(target) {
        $('#' + target).removeClass('md-show');
    }

    $('.md-close').click(function () {
        $('.md-modal').each(function () {
            hideModal($(this).attr('id'));
        });
    });
</script>

</body>
</html>
