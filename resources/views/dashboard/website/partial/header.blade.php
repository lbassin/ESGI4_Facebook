<?php /** @var \App\Http\Helpers\UserHelper $userHelper */ ?>
<div class="head">
    <div class="user-pic">
        <img src="{{ $userHelper->getPicture() }}" alt="">
        <div class="head-menu">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('docs') }}">Documentation</a>
            <a href="{{ route('support') }}">Support</a>
            <a href="{{ route('dashboard.logout') }}">Deconnexion</a>
        </div>
    </div>
    <span class="user-name">{{ $userHelper->getName() }}</span>
</div>

<script>
    $('.head').find('.user-pic').click(function () {
        $('.head').find('.head-menu').stop().toggle('active');
    });
    $('.head-menu a').on('click', function() {
        showLoader('loader');
    })
</script>