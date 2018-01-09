@extends('layouts.app')

@section('title', 'Foliobook')

@section('header_scripts')
@endsection

@section('content')
    <div id="event-list" class="wrapper">
        @include('dashboard.website.header')

        <div class="list-header">
            <div id="nav">
                <a href="{{ route('dashboard.website', ['subdomain' => $subdomain]) }}">
                    <span class="nav-back">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        <span>Retour à l'accueil</span>
                    </span>
                </a>
                <span class="nav-title">Gérer les événements</span>
                <span class="nav-create">
                    <span>Créer un évenement</span>
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                </span>
            </div>
        </div>

        <div class="list-content">
            <div class="grid">
                <?php /** @var \App\Http\Api\Event $event */ ?>
                @foreach($events as $event)
                    <article class="module desktop-4 tablet-6">
                        <div class="element-image"
                             style="background: url('{{ $event->getCover() }}');background-repeat: no-repeat;background-position: center center;background-size: cover;">
                        </div>
                        <div class="element-name">
                            <span>{{ $event->getName() }}</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
@endsection