@extends('layouts.app')

@section('title', 'Update Staff')
@section('page_css')
    
@endsection
@section('content')
    @php
        $user = $data['data']['user'];
        $user_id = $data['data']['user_id'];
        $role_id = $data['data']['role_id'];
    @endphp
    <!-- Include the header -->
    @include('partials.header')
    <div class="row mb-4 mt-4 pt-4">
        <div class="col text-start">
            <div class="d-flex gap-7 align-items-center">
                <div class="symbol symbol-100px">
                    <img src="{{getAvatar($user)}}" class="img-thumbnail img-fluid" alt="{{$user->first_name}}">
                </div>
                <div class="d-flex flex-column gap-2">
                    <h3 class="mb-0">{{ucfirst($user->first_name)}} {{ucfirst($user->last_name)}} <span class="fs-7">( {{$user->roles()->first()->name ?? "Admin"}} )</span></h3>
                    @if($user->phone_number)
                        <h4 class="mb-0">{{ucfirst($user->phone_number)}} </h4>
                    @endif
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa fa-envelope fs-5"></i>
                        <a href="mailto:{{$user->email}}" class="text-muted text-hover-primary">{{$user->email}}</a>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="col text-end">
            <a class="btn btn-primary" href="{{ route('users.index') }}"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="user_create">
        
        
        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        
            @csrf
            @method('PUT') <!-- Include this to change the method to PUT -->
                
            <div class="form-group mb-3">
                <label for="name">First Name</label>
                <input type="text" class="form-control" id="name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" >
            </div>
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep the current password">
            </div>
            <div class="form-group mb-3">
                <label for="c_password">Confirm Password</label>
                <input type="password" class="form-control" id="c_password" name="c_password" placeholder="Leave blank to keep the current password">
            </div>
            {{-- <div class="form-group mb-3">
                <label for="country_code">Country Code</label>
                <select name="country_code" class="form-control">
                    <option value="+1" {{ $user->country_code == '+1' ? 'selected' : '' }}>(+1) US</option>
                    <option value="+92" {{ $user->country_code == '+92' ? 'selected' : '' }}>(+92) Pakistan</option>
                </select>
            </div> --}}
            <div class="form-group mb-3">
                <label for="phone_number">Phone Number</label>
                <input type="tel" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required>
            </div>
            <div class="form-group  mb-3">
                <label for="avatar">Profile Picture</label>
                <input type="file" class="form-control" id="avatar" name="avatar">
            </div>
            @if($user_id != Auth::user()->id)
                <div class="form-group mb-3">
                    <label for="role">Role</label>
                    <select name="role_id" id="role" class="form-control">
                        @foreach($data['data']['roles'] as $role)
                            @if($role->name != 'Donor' && $role->name != 'Backup Role Association')
                                <option value="{{ $role->id }}"
                                    {{ old('role_id', $role_id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group  mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ $user->is_active == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Account Status</label>
                    </div>
                </div>
            @else
                <input type="hidden" name="role_id" value="{{$role_id}}" />
                <input type="hidden" name="is_active" value="{{$user->is_active == 1 ? 'on' : ''}}" />
            @endif  

            <button type="submit" class="btn btn-primary">Update</button>
        </form>

    </div>
@endsection
    