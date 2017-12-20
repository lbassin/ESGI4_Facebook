<?php
  /** @var array $albums */
?>

<?php /** @var \Facebook\GraphNodes\GraphAlbum $album */ ?>
@foreach($albums as $album)
    <h2>{{ $album->getName() }}</h2>
    <img src="{{ $album->getCoverPhoto()['picture'] }}" alt="{{ $album->getName()    }}">
@endforeach

