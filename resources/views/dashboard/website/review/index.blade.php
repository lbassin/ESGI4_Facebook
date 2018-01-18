@extends('layouts.app')

@section('title', ucfirst($subdomain) . ' - Avis')

@section('header_scripts')
@endsection

@section('content')
    <div id="review-list" class="wrapper">
        @include('dashboard.website.partial.header')

        <div class="list-header">
            <div id="nav">
                <a href="{{ route('dashboard.website', ['subdomain' => $subdomain]) }}">
                    <span class="nav-back">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        <span>Retour à l'accueil</span>
                    </span>
                </a>
                <span class="nav-title">Gérer les avis</span>
                <span class="nav-create">
                    <span>Sauvegarder</span>
                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                </span>
            </div>
        </div>

        <div class="list-content">
            <div class="grid">
                <?php /** @var \App\Http\Api\Review $review */ ?>
                @foreach($reviews as $review)
                    <article class="module desktop-4 tablet-6">
                        <div class="element-texte">
                            {{ $review->getText() }}
                        </div>
                        <div class="element-name" data-id="{{ $review->getId() }}">
                            <input type="hidden" value="{{ $review->getRating() }}">
                            <div class="visibility"><i class="fa {{ $review->isVisible() ? 'fa-eye' : 'fa-eye-slash' }}" aria-hidden="true"></i></div>
                            <span></span>
                            <div class="details"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>


    @include('dashboard.website.partial.modal', ['name' => 'details-modal'])
    <div class="md-overlay">
        <button class="md-close">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    </div>

    @include('dashboard.website.partial.loader')

    <script>
        let dataEdited = {};

        let list = $('.list-content');

        initChangeVisibility();
        initSaveAction();
        displayStars();

        function displayStars() {
            $(".element-name").each(function() {
                var stars = 0;
                for (i = 0; i < $(this).find("input").val(); i++) {
                    $(this).find("span").append("<i class='fa fa-star' aria-hidden='true'></i>");
                    stars++;              
                }
                for (stars; stars < 5; stars++) {
                    $(this).find("span").append("<i class='fa fa-star-o' aria-hidden='true'></i>");           
                }
            });
        }

        function initChangeVisibility() {
            list.find('.visibility').each(function () {
                let id = $(this).parent().data('id');

                if (id in dataEdited) {
                    setVisibility(!dataEdited[id].visible, this);
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

                dataEdited[id] = {
                    visible: !visible
                };

                setVisibility(visible, this);
            }

            list.find('.details').click(function () {
                let id = $(this).parent().data('id');
                let url = '{{ route('dashboard.website.reviews.details', ['subdomain' => $subdomain]) }}';
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
                showLoader('loader');

                let url = '{{ route('dashboard.website.reviews.save', ['subdomain' => $subdomain]) }}';

                $.post(url, {reviewsEdited: dataEdited}).done(
                    function (response) {
                        if (response.error) {
                            addError(response.message);
                            setTimeout(function () {
                                hideLoader('loader');
                            }, 3500);
                            return;
                        }

                        addSuccess(response.message);
                        setTimeout(function () {
                            window.location.href = response.url;
                        }, 350);
                    }
                ).fail(errorAjax);
            });
        }
    </script>
@endsection