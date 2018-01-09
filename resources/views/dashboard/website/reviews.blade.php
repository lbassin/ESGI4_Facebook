@extends('layouts.app')

@section('title', 'Foliobook')

@section('header_scripts')
@endsection

@section('content')
    <div id="review-list" class="wrapper">
        @include('dashboard.website.header')

        <div class="list-header">
            <div id="nav">
                <a href="{{ route('dashboard.website', ['subdomain' => $subdomain]) }}">
                    <span class="nav-back">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        <span>Retour à l'accueil</span>
                    </span>
                </a>
                <span class="nav-title">Gérer les avis</span>
            </div>
        </div>

        <div class="list-content">
            <div class="grid">
                <?php /** @var \App\Http\Api\Review $review */ ?>
                @foreach($reviews as $review)
                    <article class="module desktop-4 tablet-6">
                        <div class="element-name">
                            <span>{{ $review->getText() }}</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
@endsection