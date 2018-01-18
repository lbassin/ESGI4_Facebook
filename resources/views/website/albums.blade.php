@extends('layouts.website')

@section('content')
    <div class="wrapper">
        <h1>Mes albums</h1>

        @empty($albums)
            <div class="empty">
                <h2>Aucun article n'est disponible</h2>
            </div>
        @else
            <div class="grid">
                <?php /** @var \App\Http\Api\Album $album */ ?>
                @foreach($albums as $album)
                    <div>
                        <a href="{{ route('website.view', ['subdomain' => $subdomain, 'element' => $album->getUrl()]) }}">
                            <img src="{{ $album->getCover() }}" alt="{{ $album->getName() }}">
                        </a>
                        <p>{{ $album->getName() }}</p>
                    </div>
                @endforeach
            </div>
        @endempty
    </div>

    <style>
        .wrapper {
            width: 90%;
            margin: auto;
            padding: 2% 0;
            font-family: 'Montserrat', sans-serif;
        }

        .wrapper .grid {
            display: flex;
            flex-wrap: wrap;
        }

        .wrapper .grid div {
            box-sizing: border-box;
            width: 25%;
            height: 250px;
            padding: 12px;
            text-align: center;
        }

        .wrapper .empty {
            width: 100%;
            text-align: center;
        }

        .wrapper .grid img {
            max-width: 100%;
            height: 100%;
            object-fit: cover;
            box-shadow: 3px 3px 3px -1px rgba(0, 0, 0, 0.6);
        }

        .wrapper .grid p {
            padding-top: 6px;
        }

        h1 {
            text-align: center;
            margin-bottom: 16px;
        }
    </style>
@endsection