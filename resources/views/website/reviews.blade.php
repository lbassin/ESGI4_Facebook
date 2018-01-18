@extends('layouts.website')

@section('content')
    <ul>
        <?php /** @var \App\Http\Api\Review $review */ ?>
        @foreach($reviews as $review)
            <li>{{ $review->getText() }}</li>
        @endforeach
    </ul>
@endsection