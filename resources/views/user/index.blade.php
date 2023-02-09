@extends('layouts.main')
@section('content')
    <div>
        <a href="{{ route('user.create') }}">Add one</a>
    </div>
    <div>
        @foreach ($users as $user)
            <div><a href="{{ route('user.show', $user->id) }}">{{ $user->id }}. {{ $user->first_name }}</a></div>
        @endforeach
    </div>
@endsection