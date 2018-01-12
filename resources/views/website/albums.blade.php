@extends('website.layouts.home')

@section('content')
    <div class="wrapper">
        <h1>Mes albums</h1>

        <div class="grid">
            <?php /** @var \App\Model\Album $album */ ?>
            @foreach($albums as $album)
                <div>
                    <a href="">
                        <img src="https://scontent.xx.fbcdn.net/v/t31.0-0/p600x600/26198659_1050322651775212_4174814763142848597_o.jpg?oh=0bbdfac9c8c35a87e4dfe927abc2dc85&oe=5AE8ED37"
                             alt="">
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
        }

        h1{
            text-align: center;
            margin-bottom: 16px;
        }
    </style>
@endsection