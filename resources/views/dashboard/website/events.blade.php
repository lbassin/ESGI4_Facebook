@extends('layouts.app')

@section('title', 'Foliobook')

@section('header_scripts')
@endsection

@section('content')
    <?php dump($events); /** @var array $events */?>
    <ul>
        @foreach($events as $event) <?php /** @var \App\Http\Api\Event $event */ ?>
        <li>{{ $event->getName() }}</li>
        @endforeach
    </ul>
@endsection