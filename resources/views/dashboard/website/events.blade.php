@extends('layouts.app')

@section('title', 'Foliobook')

@section('header_scripts')
@endsection

@section('content')
    <div id="event-list" class="wrapper">
        @include('dashboard.website.header')

        <div class="list-header">
            <div id="nav">
                <a href="{{ route('dashboard.website', ['subdomain' => $subdomain]) }}">
                    <span class="nav-back">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        <span>Retour à l'accueil</span>
                    </span>
                </a>
                <span class="nav-title">Gérer les événements</span>
                <span class="nav-create">
                    <span>Sauvegarder</span>
                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                </span>
            </div>
        </div>

        <div class="list-content">
            <div class="grid">
                <?php /** @var \App\Http\Api\Event $event */ ?>
                @foreach($events as $event)
                    <article class="module desktop-4 tablet-6">
                        <div class="element-image"
                             style="background: url('{{ $event->getCover() }}');background-repeat: no-repeat;background-position: center center;background-size: cover;">
                        </div>
                        <div class="overlay" data-id="{{ $event->getId() }}">
                            <div class="visibility"><i class="fa {{ $event->isVisible() ? 'fa-eye' : 'fa-eye-slash' }}"
                                                       aria-hidden="true"></i></div>
                            <div class="border"></div>
                            <div class="details"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
                        </div>
                        <div class="element-name">
                            <span>{{ $event->getName() }}</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>

    @include('dashboard.website.modal', ['name' => 'details-modal'])
    <div class="md-overlay">
        <button class="md-close">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    </div>

    <script>
        let eventsEdited = {};

        let list = $('.list-content');

        initChangeVisibility();
        initSaveAction();

        function initChangeVisibility() {
            list.find('.visibility').each(function () {
                let id = $(this).parent().data('id');

                if (id in eventsEdited) {
                    setVisibility(!eventsEdited[id].visible, this);
                }

                $(this).click(changeVisibility)
            });

            function setVisibility(visible, visibilityDiv) {
                let icon = $(visibilityDiv).find('i');

                if (visible) {
                    icon.removeClass('fa-eye');
                    icon.addClass('fa-eye-slash');
                    icon.attr('data-changed', 1)
                } else {
                    icon.removeClass('fa-eye-slash');
                    icon.addClass('fa-eye');
                    icon.attr('data-changed', 1)
                }
            }

            function changeVisibility() {
                let icon = $(this).find('i');
                let visible = icon.hasClass('fa-eye');
                let id = $(this).parent().data('id');

                eventsEdited[id] = {
                    visible: !visible
                };

                setVisibility(visible, this);
            }

            list.find('.details').click(function () {
                let id = $(this).parent().data('id');
                let url = '{{ route('dashboard.website.events.details', ['subdomain' => $subdomain]) }}';
                let detailsModal = $('#details-modal');

                detailsModal.find('.md-content').html('');
                $.post(url, {id: id}).done(
                    function (response) {
                        detailsModal.find('.md-content').html(response);
                    }
                ).fail(errorAjax);

                showModal('details-modal');
            });
        }

        function initSaveAction() {
            $('.nav-create').click(function () {
                let url = '{{ route('dashboard.website.events.save', ['subdomain' => $subdomain]) }}';

                $.post(url, {eventsEdited: eventsEdited}).done(
                    function (response) {
                        console.log(response);
                    }
                ).fail(errorAjax());
            });
        }
    </script>
@endsection