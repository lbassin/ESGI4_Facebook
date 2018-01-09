@extends('layouts.app')

@section('title', 'Foliobook')

@section('header_scripts')
@endsection

@section('content')
    <div id="posts-list" class="wrapper">
        @include('dashboard.website.header')

        <div class="list-header">
            <div id="nav">
                <a href="{{ route('dashboard.website', ['subdomain' => $subdomain]) }}">
                    <span class="nav-back">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        <span>Retour à l'accueil</span>
                    </span>
                </a>
                <span class="nav-title">Gérer les articles</span>
                <span class="nav-create">
                    <span>Créer un article</span>
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                </span>
            </div>
        </div>

        <div class="list-content">
            <div class="grid">

            </div>
        </div>
    </div>
@endsection