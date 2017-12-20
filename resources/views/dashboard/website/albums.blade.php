<?php
/** @var array $albums */
?>
<h1>Albums</h1>
<?php /** @var \Facebook\GraphNodes\GraphNode $album */ ?>
@foreach($albums as $album)
    <h2>{{ $album->getField('name') }}</h2>
    <img src="{{ $album->getField('cover_photo')->getField('picture') }}" alt="">
    <?php dump($album->getField('preview')); ?>
@endforeach