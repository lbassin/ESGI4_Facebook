@extends('layouts.website')

@section('content')
    <div class="wrapper">
        <?php /** @var \App\Model\WebsiteHomeBlock $block */ ?>
        @foreach($blocks as $block)
            @include('website.home.templates.' . $block->getBlock()->getViewPath(), $block->getConfig())
        @endforeach
    </div>

    <style>
        .wrapper {
            width: 90%;
            margin: auto;
            text-align: center;
        }
    </style>
@endsection