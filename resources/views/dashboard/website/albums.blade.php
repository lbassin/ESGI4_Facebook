@extends('layouts.app')

@section('title', 'Foliobook')

@section('header_scripts')
@endsection

@section('content')
    <div class="wrapper">
        <div class="head">
            <div class="user-pic">
                <img src="{{ $userpic }}" alt="">
            </div>
            <span class="user-name">{{ $name }}</span>
        </div>

        <div class="wrapper-albums">
            <div class="albums-nav">
                <span class="albums-back">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    <span>Retour à l'accueil</span>
                </span>
                <span class="albums-title">Gérer les albums</span>
                <span class="albums-create">
                    <span>Créer un album</span>
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                </span>
            </div>
            <div class="album-pictures">

            </div>
        </div>

        <div class="wrapper-pictures">

        </div>
    </div>

@endsection