@extends('layouts/app')

@section('title', 'Foliobook')

@section('header_scripts')
@endsection

@section('content')
    <div class="image-gradient">
    </div>
    <div class="wrapper-dashboard">
        <div class="head">
            <div class="user-pic">
                <img src="{{ $userpic }}" alt="">
            </div>
            <span class="user-name">{{ $name }}</span>
        </div>

        <div class="select-dashboard">
            <div class="select-choice">
                <h1>Choisissez votre site</h1>
                <select name="sources" id="sources" class="custom-select sources" placeholder="Source Type">
                    <option value="profile">Profile</option>
                    <option value="word">Word</option>
                    <option value="hashtag">Hashtag</option>
                    <option value="hashtag">Hashtag</option>
                    <option value="hashtag">Hashtag</option>
                </select>
            </div>


            <div id="dev-laurent">
                @forelse($pages as $page)
                    <a href="{{ route('dashboard.new', ['id' => $page['id']]) }}">{{ $page['name'] }}</a>
                @endforeach
            </div>

        </div>
    </div>


    <script>

        $(".custom-select").each(function () {
            var classes = $(this).attr("class"),
                id = $(this).attr("id"),
                name = $(this).attr("name");
            var template = '<div class="' + classes + '">';
            template += '<span class="custom-select-trigger">' + $(this).attr("placeholder") + '</span>';
            template += '<div class="custom-options">';
            $(this).find("option").each(function () {
                template += '<span class="custom-option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
            });
            template += '</div></div>';

            $(this).wrap('<div class="custom-select-wrapper"></div>');
            $(this).hide();
            $(this).after(template);
        });
        $(".custom-option:first-of-type").hover(function () {
            $(this).parents(".custom-options").addClass("option-hover");
        }, function () {
            $(this).parents(".custom-options").removeClass("option-hover");
        });
        $(".custom-select-trigger").on("click", function () {
            $('html').one('click', function () {
                $(".custom-select").removeClass("opened");
            });
            $(this).parents(".custom-select").toggleClass("opened");
            event.stopPropagation();
        });
        $(".custom-option").on("click", function () {
            $(this).parents(".custom-select-wrapper").find("select").val($(this).data("value"));
            $(this).parents(".custom-options").find(".custom-option").removeClass("selection");
            $(this).addClass("selection");
            $(this).parents(".custom-select").removeClass("opened");
            $(this).parents(".custom-select").find(".custom-select-trigger").text($(this).text());
        });

    </script>
@endsection