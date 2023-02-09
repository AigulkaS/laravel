@extends('layouts.main')
@section('content')
    <div>
            <div>{{ $user->id }}. {{ $user->first_name }}</div>
            <div>{{ $user->email }}</div>
    </div>
    <div>
      <a href="{{ route('user.edit', $user->id) }}">Edit</a>
    </div>
    <div>
      <form action="{{ route('user.delete', $user->id) }}" method="POST">
          @csrf
          @method('delete')
          <input type="submit" value="Delete">
      </form>
    </div>

    <div>
      <a href="{{ route('user.index') }}">Back</a>
    </div>
@endsection