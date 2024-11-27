@extends('layouts.app')

@section('title', 'Create User')
@section('page_css')
    <link href="https://cdn.datatables.net/2.1.4/css/dataTables.bootstrap5.css" />
@endsection
@section('content')
    <!-- Include the header -->
    @include('partials.header')
    <div class="row mb-4 mt-4 pt-4">
        <div class="col text-start">
            <h3>Staff Members</h3>
        </div>
        <div class="col text-end">
            <a class="btn btn-primary" href="{{ route('users.index') }}"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="user_create">
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="name">First Name</label>
                <input type="text" class="form-control" id="name" name="first_name" value="{{ old('first_name') }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" >
            </div>
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="c_password">Confirm Password</label>
                <input type="password" class="form-control" id="c_password" name="c_password" value="{{ old('c_password') }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="phone_number">Phone Number</label>
                <input type="tel" id="phone_number" class="form-control" name="phone_number" value="{{ old('phone_number') }}" required>
            </div>
            <div class="form-group  mb-3">
                <label for="avatar">Profile Picture</label>
                <input type="file" class="form-control" id="avatar" name="avatar">
            </div>
            <div class="form-group mb-3">
                <label for="role">Role</label>
                <select name="role_id" id="role" class="form-control">
                    @foreach($roles as $role)
                        @if($role->name != 'Donor' && $role->name != 'Backup Role Association')
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group  mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_active" value="1" name="is_active" {{ old('is_active') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Account Status</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>

    </div>
@endsection
    