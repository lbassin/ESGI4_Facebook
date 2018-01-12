<?php
/** @var array $photos */
/** @var \App\Model\Album $album */
?>

<div class="wrapper">
    <h1>{{ $album->getTitle() }}</h1>
    <h2>{{ $album->getDescription() }}</h2>

    <div class="grid">
        <?php /** @var \App\Http\Api\Photo $photo */ ?>
        @foreach($photos as $photo)
            <div>
                <img src="{{ $photo->getLink(\App\Http\Api\Photo::SIZE_MEDIUM) }}" alt="{{ $photo->getAlt() }}">
            </div>
        @endforeach
    </div>
</div>

<style>
    .wrapper {
        width: 75%;
        margin: auto;
    }

    .wrapper .grid {
        display: flex;
        flex-wrap: wrap;
        margin-top: 32px;
    }

    .wrapper .grid div {
        box-sizing: border-box;
        width: 33%;
    }

    .wrapper .grid img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    h1 {
        margin-top: 64px;
    }

    h2 {
        margin-top: 32px;
    }

    h1, h2 {
        font-family: 'Montserrat', sans-serif;
        text-align: center;
        font-weight: 300;
    }
</style>
