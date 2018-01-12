<?php
/** @var array $photos */
/** @var \App\Model\Album $album */
?>

<div class="wrapper">
    <h2>{{ $album->getDescription() }}</h2>

    <div class="grid">
        <?php /** @var \App\Http\Api\Photo $photo */ ?>
        @foreach($photos as $photo)
            <div>
                <img src="{{ $photo->getLink(\App\Http\Api\Photo::SIZE_MEDIUM) }}" alt="{{ $photo->getAlt() }}">
                <p>Test</p>
            </div>
        @endforeach
    </div>
</div>

<style>
    .wrapper {
        width: 65%;
        margin: auto;
    }

    .wrapper .grid {
        display: flex;
        flex-wrap: wrap;
        margin-top: 32px;
    }

    .wrapper .grid div {
        box-sizing: border-box;
        padding: 0 3%;
        height: 250px;
        width: 33%;
        margin-bottom: 64px;
    }

    .wrapper .grid div p {
        margin-top: 6px;
        text-align: center;
        font-weight: 600;
    }

    .wrapper .grid img {
        margin: auto;
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
