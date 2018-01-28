@extends('layouts.app')

@section('title', ucfirst($subdomain) . ' - Accueil')

@section('content')
    <div class="wrapper">
        @include('dashboard.website.partial.header')

        <div class="list-header">
            <div id="nav">
                <a href="{{ route('dashboard.website', ['subdomain' => $subdomain]) }}">
                    <span class="nav-back">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        <span>Retour à l'accueil</span>
                    </span>
                </a>
                <span class="nav-title">Page d'accueil</span>
                <span class="nav-create">
                    <span>Sauvegarder</span>
                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                </span>
            </div>
        </div>

        <div id="home-config">
            <h2>Vous n'avez aucun élément visible</h2>
            <div class="add-element">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </div>
        </div>
    </div>
@endsection