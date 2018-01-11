<?php /** @var \App\Http\Api\Photo $photo */ ?>
<h1>Image</h1>
<img src="{{ $photo->getLink(\App\Http\Api\Photo::SIZE_LARGE) }}" alt="">
