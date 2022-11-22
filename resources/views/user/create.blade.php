@extends('layouts.main')
@section('content')
    <div>
    <form action="{{ route('user.store') }}", method="POST">
        @csrf
        <div class="form-group">
          <label for="role">Role</label>
          <select name="role_id" class="form-control" id="role">
            @foreach ($roles as $role)
            <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
            <label for="hospital">Hospital</label>
            <select name="hospital_id" class="form-control" id="hospital">
              @foreach ($hospitals as $hospital)
            <option value="{{ $hospital->id }}">{{ $hospital->full_name }}</option>
            @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="last_name">Last name</label>
            <input name="last_name" type="text" class="form-control" id="last_name" placeholder="Last name">
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input name="first_name" type="text" class="form-control" id="name" placeholder="Name">
        </div>
        <div class="form-group">
            <label for="patronymic">Patronymic</label>
            <input name="patronymic" type="text" class="form-control" id="patronymic" placeholder="Patronymic">
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input name="phone" type="text" class="form-control" id="phone" placeholder="Phone">
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input name="email" type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input name="password" type="password" class="form-control" id="password" placeholder="Password">
          </div>
          <button type="submit" class="btn btn-primary">Add</button>
      </form>
    </div>

    <div>
      <a href="{{ route('user.index') }}">Back</a>
    </div>
@endsection