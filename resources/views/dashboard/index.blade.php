@extends('layouts.app')

@section('title', 'Foliobook')

@section('header_scripts')
@endsection

@section('content')
    <div class="image-gradient">
    </div>
    <div class="wrapper-dashboard">
        <div class="head">
            <div class="user-pic">
                <img src="{{ $userHelper->getPicture() }}" alt="">
            </div>
            <span class="user-name">{{ $userHelper->getName() }}</span>
        </div>

        <div class="select-dashboard">
            <div class="select-choice">
                <div class="select-title">
                    <h1>Choisissez votre site</h1>
                </div>
                <div class="select-action">
                    <select id="website-select">
                        <option value="" disabled selected>Website !</option>
                        @foreach($websites as $website)
                            <option value="{{ $website['subdomain'] }}">{{ $website['name'] }}</option>
                        @endforeach
                    </select>
                    <div class="add-page">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="md-modal md-effect-12">
        <div class="md-content list">
            <h1>NOUVEAU SITE</h1>
            <h2>Choisissez la page à synchroniser</h2>
            <ul id="pages">
                @foreach($pages as $page)
                    <li class="page" data-id="{{ $page['id'] }}">
                        <div>
                            <img src="{{ $page['picture']['data']['url'] }}" alt="">
                            <span>{{ $page['name'] }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="md-content config" style="opacity: 0;">
            <h1>Choix de l'url</h1>
            <form action="{{ route('dashboard.new') }}">
                <p>
                    https://<input title="Website URL" type="text" style="text-align: center" name="new-page-url">.foliobook.fr/
                </p>
                <input type="hidden" name="new-page-id" value="">

                <div>
                    <button id="submit-new-page">
                        Créer mon site
                    </button>
                </div>
            </form>

            <div id="messages">
                <div class="success">
                    <ul></ul>
                </div>
                <div class="errors">
                    <ul></ul>
                </div>
            </div>
        </div>
    </div>

    <div class="md-overlay">
        <button class="md-close">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    </div>

    <div class="loading-overlay">
        <div class="loading">
            <div class="animation">
                <div class="circle one"></div>
            </div>
            <div class="animation">
                <div class="circle two"></div>
            </div>
            <div class="animation">
                <div class="circle three"></div>
            </div>
            <div class="animation">
                <div class="circle four"></div>
            </div>
            <div class="animation">
                <div class="circle five"></div>
            </div>
        </div>
    </div>

    <script>
        function initDropdown() {
            let select = $('#website-select');
            let dropdownDiv = $('<div>').addClass('dropdown');
            let currentSpan = $('<span>').addClass('current');
            let listDiv = $('<div>').addClass('list');
            let optionsList = $('<ul>');

            let options = $(select).find('option');
            let selectedText = $(select).find('option:selected').text();

            listDiv.append(optionsList);
            currentSpan.text(selectedText);
            dropdownDiv.append(currentSpan).append(listDiv);

            $(select).after(dropdownDiv);

            options.each(function (index, option) {
                option = $(option);

                let listItem = $('<li>').addClass('option');
                listItem.attr('data-value', option.val());
                listItem.text(option.text());

                if (option.is(':selected')) {
                    listItem.addClass('selected');
                }

                if (option.attr('disabled')) {
                    listItem.addClass('disabled');
                }

                optionsList.append(listItem);
            });

            addListener();
        }

        function addListener() {
            let dropdown = $('.dropdown');

            dropdown.on('click', function () {
                let dropdown = $(this);

                dropdown.toggleClass('open');
                if (dropdown.hasClass('open')) {
                    // Allows keyboard events
                    dropdown.find('.option').attr('tabindex', 0);
                    dropdown.find('.selected').focus();
                } else {
                    dropdown.find('.option').removeAttr('tabindex');
                    dropdown.focus();
                }
            });

            $(document).on('click', function (event) {
                if ($(event.target).closest('.dropdown').length === 0) {
                    $('.dropdown').removeClass('open');
                    $('.dropdown .option').removeAttr('tabindex');

                    event.stopPropagation();
                }
            });

            $('.dropdown .option').on('click', function () {
                let selected = $(this);
                let dropdown = selected.closest('.dropdown');

                selected.closest('.list').find('.selected').removeClass('selected');
                selected.addClass('selected');

                dropdown.find('.current').text(selected.text());
                dropdown.prev('select').val(selected.val()).trigger('change');

                window.location.href = window.URLs.websiteAdmin + '/' + selected.data('value');
            });

            dropdown.on('keydown', function (event) {
                let focusOption = $(this).find('.list .option:focus')[0];

                if (!focusOption) {
                    focusOption = $(this).find('.list .option.selected')[0]
                }

                switch (event.keyCode) {
                    case 32: // Space
                    case 13: // Enter
                        if ($(this).hasClass('open')) {
                            $(focusOption).trigger('click');
                        } else {
                            $(this).trigger('click');
                        }
                        break;
                    case 40: // Down
                        if (!$(this).hasClass('open')) {
                            $(this).trigger('click');
                        } else {
                            $(focusOption).next().focus();
                        }
                        break;
                    case 38: // Up
                        if (!$(this).hasClass('open')) {
                            $(this).trigger('click');
                        } else {
                            $(focusOption).prev().focus();
                        }
                        break;
                    case 27: // Echap
                        if ($(this).hasClass('open')) {
                            $(this).trigger('click');
                        }
                        break;
                }
            });
        }

        function initNewPage() {
            let pages = $('#pages .page');
            let listModal = $('.md-modal .md-content.list');
            let configModal = $('.md-modal .md-content.config');
            let submitNewPage = $('#submit-new-page');

            pages.click(function () {
                let sourceId = $(this).attr('data-id');
                $('input[name="new-page-id"]').val(sourceId);

                $.post('{{ route('dashboard.suggest.url') }}', {id: sourceId}).done(
                    function (response) {
                        $('input[name="new-page-url"]').val(response.url);
                    }
                ).always(
                    function () {
                        listModal.css({opacity: 0});
                        setTimeout(function () {
                            listModal.hide();
                            configModal.animate({opacity: 1}, 250);
                        }, 300);
                    }
                );

            });

            submitNewPage.click(function (event) {
                event.preventDefault();

                let id = this.form['new-page-id'].value;
                let url = this.form['new-page-url'].value;

                $.post(this.form.action, {id: id, url: url}).done(
                    function (response) {
                        if (response.error) {
                            console.log(response);
                            addError(response.message);
                            return;
                        }

                        addSuccess("Website created");
                        setTimeout(function(){
                            window.location.href = response.url;
                        }, 750);
                    }).fail(
                    function (response) {
                        addError(response.responseJSON.message);
                    }
                );
            });
        }

        function addSuccess(message) {
            let wrapper = $('#messages .success ul');

            let success = $('<li>').text(message);
            wrapper.append(success);
        }

        function addError(message) {
            let wrapper = $('#messages .errors ul');

            let error = $('<li>').text(message);
            wrapper.append(error);
        }

        $(function () {
            $('.add-page').on('click', function () {
                $('.md-modal').addClass('md-show');
            });

            $(document).on('keydown', function (event) {
                if (event.keyCode === 27) {
                    $('.md-modal').removeClass('md-show');
                }
            });

            $('.md-close').on('click', function () {
                $('.md-modal').removeClass('md-show');
            });

            initDropdown();
            initNewPage();
        });
    </script>

@endsection