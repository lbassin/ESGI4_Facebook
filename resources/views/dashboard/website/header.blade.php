<div class="head">
    <div class="user-pic">
        <img src="{{ $userHelper->getPicture() }}" alt="">
        <div class="head-menu">
            <a href="#">Docs</a>
            <a href="#">Support</a>
            <a href="{{ route('dashboard.logout') }}">Deconnexion</a>
        </div>
    </div>
    <span class="user-name">{{ $userHelper->getName() }}</span>
</div>

<script>
    $('.head').find('.user-pic').click(function () {
        $('.head').find('.head-menu').toggle('active');
    });
</script>