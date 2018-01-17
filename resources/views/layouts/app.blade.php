<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ env('APP_NAME') }}</title>

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-112512559-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-112512559-1');
    </script>

    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">

    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
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

<script>
    function showLoader(target) {
        $('#' + target).css("display", "block");
        $('#' + target).animate({
            opacity: 1
          }, 500);
    }

    function hideLoader(target) {
        $('#' + target).animate({
            opacity: 0
        }, 500, function() {
            $('#' + target).css("display", "none");
        });
    }

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
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
