@extends('website.layouts.home')

@section('content')
    <hr>
    <ul>
        <?php /** @var \App\Model\Album $album */ ?>
        @foreach($albums as $album)
            <li>
                <a href="{{ route('website.view', ['subdomain' => $subdomain, 'element' => $album->getUrl()]) }}">{{ $album->getTitle() }}</a>
            </li>
        @endforeach
    </ul>
@endsection