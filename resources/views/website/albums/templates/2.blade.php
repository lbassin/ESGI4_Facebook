<?php /** @var array $photos */ ?>
<h1>Template 1</h1>

<?php /** @var \App\Http\Api\Photo $photo */ ?>
@foreach($photos as $photo)
    <div>
        <img src="{{ $photo->getLink(\App\Http\Api\Photo::SIZE_MEDIUM) }}" alt="{{ $photo->getAlt() }}">
    </div>
@endforeach