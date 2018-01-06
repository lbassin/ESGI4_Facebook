@extends('layouts.app')

@section('title', 'Foliobook')

@section('header_scripts')
@endsection

@section('content')
<?php /** @var array $album */ ?>

<div class="wrapper">
    <div class="head">
        <div class="user-pic">
            <img src="{{ $userHelper->getPicture() }}" alt="">
        </div>
        <span class="user-name">{{ $userHelper->getName() }}</span>
    </div>

    <div class="container website-home">
        <div class="nav-button-mobile">
            <h2>menu</h2>
        </div>
        <nav class="website-home-nav">
            <button class="md-close">
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>
            <ul>
                <li><a href="{{ route('dashboard.website.home', ['subdomain' => $subdomain]) }}">Gestion de l'accueil</a></li>
                <li><a href="{{ route('dashboard.website.albums', ['subdomain' => $subdomain]) }}">Gestion des albums</a></li>
                <li><a href="{{ route('dashboard.website.articles', ['subdomain' => $subdomain]) }}">Gestion des articles</a></li>
                <li><a href="{{ route('dashboard.website.events', ['subdomain' => $subdomain]) }}">Gestion des evenements</a></li>
                <li><a href="{{ route('dashboard.website.reviews', ['subdomain' => $subdomain]) }}">Gestion des avis</a></li>
            </ul>
        </nav>
        <div class="menu-albums">
            <div class="menu-albums-title">
                <h2>Mes Derniers Albums</h2>
            </div>
            @foreach($albums as $album)
                <div class="album">
                    <div class="title">
                        <div class="inner">
                            <h2>{{ $album->getName() }}</h2>
                        </div>
                        <div class="gradient"></div>
                    </div>
                    <div class="image">
                        <?php $cover = $album->getCover(); ?>
                        @if ($cover !== "")
                            <img src="{{ $cover }}" alt="">
                        @else
                            <img src="http://via.placeholder.com/350x150" alt="">
                        @endif
                    </div>
                </div>

            @endforeach
        </div>
    </div>


    <script>
        let mobileNav = false;
        let desktopNav = false;
        let websiteNavExp = 'nav.website-home-nav';
        function isMediumWidth(){
            return window.innerWidth <= 768;
        }
        $('nav button.md-close').click(function(){
            if(isMediumWidth()){
                if($(websiteNavExp).hasClass('m-open')){
                    $(websiteNavExp).removeClass('m-open');
                }
            }
        });

        $('div.nav-button-mobile').click(function(){
           if(isMediumWidth()){
               if(!$(websiteNavExp).hasClass('m-open')){
                   $(websiteNavExp).addClass('m-open');
               }
           }
        });

        function moveNavTo(position){
            switch(position){
                case 'desktop':
                    if(!$(websiteNavExp).parent().hasClass('website-home')){
                        $(websiteNavExp).insertAfter('.nav-button-mobile')
                    }
                    break;
                case 'mobile':
                    if($(websiteNavExp).parent().hasClass('website-home')){
                        $(websiteNavExp).insertAfter('.head');
                    }
                    break;
            }
        }
        function checkNavState(){
            if(isMediumWidth() && !mobileNav){
                moveNavTo('mobile');
                mobileNav = true;
                desktopNav = false;
            }

            if(!isMediumWidth() && !desktopNav){
                moveNavTo('desktop');
                desktopNav = true;
                mobileNav = false;
            }
        }
        $(window).resize(checkNavState);
        $(document).ready(checkNavState);
    </script>

</div>


@endsection