@extends('layouts.app')

@section('title', 'Foliobook')

@section('header_scripts')
@endsection

@section('content')
    <?php dump($reviews); /** @var array $reviews */?>
    <ul>
        @foreach($reviews as $review) <?php /** @var \App\Http\Api\Review $review */ ?>
        <li>{{ $review->getReviewerName() }}</li>
        <li>{{ $review->getText() }}</li>
        <li>{{ $review->getRating() }}</li>
        <li><img src="{{ $review->getReviewerPicture() }}" alt=""></li>
        <li>{{ $review->getCreatedTime() }}</li>
        @endforeach
    </ul>
@endsection