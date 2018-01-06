@extends('layouts.app')

@section('title', 'Foliobook')

@section('header_scripts')
@endsection

@section('content')
    <div id="album-edit" class="wrapper">
        <div class="head">
            <div class="user-pic">
                <img src="{{ $userHelper->getPicture() }}" alt="">
            </div>
            <span class="user-name">{{ $userHelper->getName() }}</span>
        </div>

        <div class="container album-edit">
            <div class="nav-button-mobile">
                <h2>menu</h2>
            </div>
            <nav class="album-edit-nav">
                <button class="md-close">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
                <ul>
                    <li><a class="active">Choix du template</a></li>
                    <li><a>Selection des images</a></li>
                    <li><a>Options</a></li>
                </ul>
            </nav>
            <div class="album-edit-content step-1">
                <div class="album-edit-content-title">
                    <h2>Mes templates</h2>
                </div>
                @foreach($templates as $template)
                    <div class="preview template" data-target="modal-preview" data-id="{{ $template['id'] }}">
                        <div class="title">
                            <div class="inner">
                            </div>
                            <div class="gradient"></div>
                        </div>
                        <div class="image">
                            <img src="{{ $template['image'] }}" alt="">
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="album-edit-content step-2" style="display: none;">
                <div class="album-edit-content-title">
                    <h2>Mes images</h2>
                </div>
                @for($i = 0; $i < 5; $i++)
                    <div class="preview image" data-target="modal-preview" data-id="1">
                        <div class="title">
                            <div class="inner">
                            </div>
                            <div class="gradient"></div>
                        </div>
                        <div class="image">
                            <img src="http://via.placeholder.com/350x150" alt="">
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <div id="modal-preview" class="md-modal md-effect-12">
        <div class="md-content">
            <h1>Mise en page</h1>
            <div>
                <h2>Ordinateur</h2>
                <img src="{{ asset('templates/1/desktop.png') }}" alt="" style="width: 50%">
            </div>
            <div>
                <h2>Mobile</h2>
                <img src="{{ asset('templates/1/mobile.png') }}" alt="" style="height: 50%">
            </div>
            <div>
                <button id="template-cancel">Cancel</button>
                <button id="template-submit">Valider</button>
            </div>
        </div>
    </div>

    <div class="md-overlay">
        <button class="md-close">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    </div>

    <script> // Responsive
        let mobileNav = false;
        let desktopNav = false;
        let websiteNavExp = 'nav.album-edit-nav';

        function isMediumWidth() {
            return window.innerWidth <= 768;
        }

        $('nav button.md-close').click(function () {
            if (isMediumWidth()) {
                if ($(websiteNavExp).hasClass('m-open')) {
                    $(websiteNavExp).removeClass('m-open');
                }
            }
        });

        $('div.nav-button-mobile').click(function () {
            if (isMediumWidth()) {
                if (!$(websiteNavExp).hasClass('m-open')) {
                    $(websiteNavExp).addClass('m-open');
                }
            }
        });

        function moveNavTo(position) {
            switch (position) {
                case 'desktop':
                    if (!$(websiteNavExp).parent().hasClass('album-edit')) {
                        $(websiteNavExp).insertAfter('.nav-button-mobile')
                    }
                    break;
                case 'mobile':
                    if ($(websiteNavExp).parent().hasClass('album-edit')) {
                        $(websiteNavExp).insertAfter('.head');
                    }
                    break;
            }
        }

        function checkNavState() {
            if (isMediumWidth() && !mobileNav) {
                moveNavTo('mobile');
                mobileNav = true;
                desktopNav = false;
            }

            if (!isMediumWidth() && !desktopNav) {
                moveNavTo('desktop');
                desktopNav = true;
                mobileNav = false;
            }
        }

        $(window).resize(checkNavState);
        $(document).ready(checkNavState);
    </script>

    <script> // Specific
        let step = 1;

        $('.template').click(function () {
            let target = $(this).data('target');
            let templateId = $(this).data('id');

            showModal(target);
        });

        $('#template-cancel').click(function () {
            $('.md-close').trigger('click');
        });

        $('#template-submit').click(function () {
            let nav = $('nav ul');
            nav.find('li a.active').removeClass('active');
            $(nav.find('li a')[1]).addClass('active');

            step += 1;
            updateStep();

            $('.md-close').trigger('click');
        });

        function updateStep() {
            $('.step-' + (step - 1)).hide();
            $('.step-' + step).show();
        }
    </script>

    <script> // Global
        function showModal(target) {
            $('#' + target).addClass('md-show');
        }

        function hideModal(target) {
            $('#' + target).removeClass('md-show');
        }

        $('.md-close').click(function () {
            $('.md-modal').each(function () {
                hideModal($(this).attr('id'));
            });
        });
    </script>

@endsection