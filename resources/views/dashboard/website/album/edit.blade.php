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
                    <li id="menu-templates"><a class="active">Choix du template</a></li>
                    <li id="menu-images"><a>Selection des images</a></li>
                    <li id="menu-options"><a>Options</a></li>
                </ul>
            </nav>
            <div class="album-edit-content step-1">
                <div class="album-edit-content-title">
                    <h2>Mes templates</h2>
                </div>
                @foreach($templates as $template) <?php /** @var \App\Model\Template $template */ ?>
                <div class="preview template" data-target="modal-preview" data-id="{{ $template->getId() }}">
                    <div class="title">
                        <div class="inner">
                        </div>
                        <div class="gradient"></div>
                    </div>
                    <div class="image">
                        <img src="{{ $template->getDesktopPreview() }}" alt="">
                    </div>
                </div>
                @endforeach
            </div>
            <div class="album-edit-content step-2" style="display: none;">
                <div class="album-edit-content-title">
                    <h2>Mes images</h2>
                </div>
                @foreach($album->getPhotos() as $photo) <?php /** @var \App\Http\Api\Photo $photo */ ?>
                    <div class="preview image" data-target="modal-preview" data-id="1">
                        <div class="title">
                            <div class="inner">
                            </div>
                            <div class="gradient"></div>
                        </div>
                        <div class="image">
                            <img src="{{ $photo->getLink(\App\Http\Api\Photo::SIZE_MEDIUM) }}" alt="">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div id="modal-preview" class="md-modal md-effect-12">
        <div class="md-content">
            <h1>Mise en page</h1>
            <div>
                <h2>Ordinateur</h2>
                <img class="desktop-preview" src="" alt="" style="width: 50%">
            </div>
            <div>
                <h2>Mobile</h2>
                <img class="mobile-preview" src="" alt="" style="height: 50%">
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
        let templateId = null;

        $('.template').click(function () {
            let target = $(this).data('target');
            templateId = $(this).data('id');

            $.post('{{ route('dashboard.website.albums.template', ['subdomain' => $subdomain]) }}', {id: templateId}).done(
                function (response) {
                    let modal = $('#modal-preview');

                    modal.find('.desktop-preview').attr('src', response.desktop_preview);
                    modal.find('.mobile-preview').attr('src', response.mobile_preview);
                }
            ).fail(
                function () {
                    alert('An error occurred');
                }
            );

            showModal(target);
        });

        $('#template-cancel').click(function () {
            $('.md-close').trigger('click');
        });

        $('#template-submit').click(function () {
            let nav = $('nav ul');
            nav.find('li a.active').removeClass('active');
            $(nav.find('li a')[1]).addClass('active');

            showImages();

            $('.md-close').trigger('click');
        });

        function showTemplates() {
            $('.step-1').show();
            $('.step-2').hide();
            $('.step-3').hide();

            $('nav ul li a').removeClass('active');
            $('nav ul #menu-templates > a').addClass('active');
        }

        function showImages() {
            $('.step-1').hide();
            $('.step-2').show();
            $('.step-3').hide();

            $('nav ul li a').removeClass('active');
            $('nav ul #menu-images > a').addClass('active');
        }

        function showOptions() {
            $('.step-1').hide();
            $('.step-2').hide();
            $('.step-3').show();

            $('nav ul li a').removeClass('active');
            $('nav ul #menu-options > a').addClass('active');
        }

        $("#menu-templates").click(showTemplates);
        $("#menu-images").click(showImages);
        $("#menu-options").click(showOptions);
    </script>

    <script> // Global
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

@endsection