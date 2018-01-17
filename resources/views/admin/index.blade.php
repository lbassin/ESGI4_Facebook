@extends('layouts.app')

@section('title', 'Administration')

@section('header_scripts')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
    <div id="admin" class="wrapper">
        <h1>Administration</h1>

        <div class="group">
            <h2>Websites</h2>
            <table>
                <tr>
                    <th>User ID</th>
                    <th>Page ID</th>
                    <th>URL</th>
                    <th>Mise à jour</th>
                    <th>Action</th>
                </tr>
                <?php /** @var \App\Model\Website $website */ ?>
                @foreach($websites as $website)
                    <tr>
                        <td>{{ $website->getUserId() }}</td>
                        <td>{{ $website->getSourceId() }}</td>
                        <td>{{ $website->getSubDomain() }}</td>
                        <td>{{ $website->getUpdatedAt() }}</td>
                        <td><a href="{{ route('website.home', ['subdomain' => $website->getSubDomain()]) }}">Voir</a></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

@endsection
