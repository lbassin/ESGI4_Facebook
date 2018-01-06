@extends('layouts.app')

@section('title', 'Foliobook')

@section('header_scripts')
@endsection

@section('content')
    <?php dump($events); /** @var array $events */?>
        @foreach($events as $event) <?php /** @var \App\Http\Api\Event $event */ ?>
        <ul>
            <li>{{ $event->getName() }}</li>
            <li>{{ $event->getStartDate() }} -> {{ $event->getEndDate() }}</li>
            <li>{{ $event->getPlaceName() }}</li>
            <li><img src="{{ $event->getCover() }}" alt=""></li>
        </ul>
        <hr>
        @endforeach
@endsection