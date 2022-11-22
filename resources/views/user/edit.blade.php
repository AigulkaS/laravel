@extends('layouts.main')
@section('content')
    <div>
    <form action="{{ route('user.update', $user->id) }}", method="POST">
        @csrf
        @method('patch')
        <div class="form-group">
          <label for="role">Role</label>
          <select name="role_id" class="form-control" id="role" value="{{ $user->role_id }}">
            @foreach ($roles as $role)
            <option {{ $role->id === $user->role_id ? 'selected' : '' }} value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
            <label for="hospital">Hospital</label>
            <select name="hospital_id" class="form-control" id="hospital" value="{{ $user->hospital_id }}">
              @foreach ($hospitals as $hospital)
            <option {{ $hospital->id === $user->hospital_id ? 'selected' : '' }} value="{{ $hospital->id }}">{{ $hospital->full_name }}</option>
            @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="last_name">Last name</label>
            <input name="last_name" type="text" class="form-control" id="last_name" placeholder="Last name" value="{{ $user->last_name }}">
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input name="first_name" type="text" class="form-control" id="name" placeholder="Name" value="{{ $user->first_name }}">
        </div>
        <div class="form-group">
            <label for="patronymic">Patronymic</label>
            <input name="patronymic" type="text" class="form-control" id="patronymic" placeholder="Patronymic" value="{{ $user->patronymic }}">
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input name="phone" type="text" class="form-control" id="phone" placeholder="Phone" value="{{ $user->phone }}">
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input name="email" type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email" value="{{ $user->email }}">
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input name="password" type="password" class="form-control" id="password" placeholder="Password" value="{{ $user->password }}">
          </div>
          <button type="submit" class="btn btn-primary">Update</button>
      </form>
    </div>

    <div>
      <a href="{{ route('user.index') }}">Back</a>
    </div>
@endsection