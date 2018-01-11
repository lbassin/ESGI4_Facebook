<?php /** @var \App\Http\Api\Event $event */ ?>
<h1>{{ $event->getName() }}</h1>

@if(!empty($event->getPlaceName()))
<h2>Lieu : {{ $event->getPlaceName() }}</h2>
@endif

<h2>Debut : {{ $event->getStartDate() }}</h2>
<h2>Fin : {{ $event->getEndDate() }}</h2>

<img src="{{ $event->getCover() }}" alt="">