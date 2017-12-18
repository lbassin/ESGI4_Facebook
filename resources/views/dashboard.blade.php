<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Foliobook</title>
    </head>
    <body>

    <h2>User : </h2>
    <ul>
        @foreach ($data as $tmp)
            <li>{{ $tmp }}</li>
        @endforeach
    </ul>

    <hr>

    <h2>Available Pages</h2>
    <ul>
        @foreach ($pages as $tmp)
            <li>{{ $tmp['name'] }}</li>
        @endforeach
    </ul>

    <hr>

    <h2>Website :</h2>
    <ul>
        @foreach ($websites as $tmp)
            <li>{{ $tmp }}</li>
        @endforeach
    </ul>

    </body>
</html>
