@extends('website.layouts.home')

@section('content')
    <?php /** @var \App\Http\Api\Event $event */ ?>
    <ul>
        @foreach($events as $event)
            <li>{{ $event->getName() }}</li>
        @endforeach
    </ul>
@endsection