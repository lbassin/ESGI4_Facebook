@extends('website.layouts.home')

@section('content')
    <div class="wrapper">
        <h1>Mes albums</h1>

        <div class="grid">
            <?php /** @var \App\Http\Api\Album $album */ ?>
            @foreach($albums as $album)
                <div>
                    <a href="{{ route('website.view', ['subdomain' => $subdomain, 'element' => $album->getUrl()]) }}">
                        <img src="{{ $album->getCover() }}" alt="{{ $album->getName() }}">
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        .wrapper {
            width: 90%;
            margin: auto;
            padding: 2% 0;
        }

        .wrapper .grid {
            display: flex;
            flex-wrap: wrap;
        }

        .wrapper .grid div {
            box-sizing: border-box;
            width: 25%;
            padding: 12px;
        }

        .wrapper .grid img {
            max-width: 100%;
            box-shadow: 3px 3px 3px -1px rgba(0, 0, 0, 0.6);
        }

        h1 {
            text-align: center;
            margin-bottom: 16px;
        }
    </style>
@endsection