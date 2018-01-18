@extends('layouts.website')

@section('content')
<?php
/** @var \App\Model\Album $album */
/** @var array $photos */
?>

@include('website.albums.templates.' . $album->getTemplateId(), ['photos' => $photos])
@endsection