@extends('layouts.app')

@section('title', 'Update Profile')
@section('page_css')
    
@endsection
@section('content')
    <!-- Include the header -->
    @include('partials.header')
    <div class="row mb-4 mt-4 pt-4">
        <div class="col text-start">
            <h3>Staff Members</h3>
        </div>
        <div class="col text-end">
            <a class="btn btn-primary" href="{{ route('donors.index') }}"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="user_create">
        @php
        
        @endphp
        
        <form action="{{ route('donors.updateProfile') }}" method="POST">
        
            @csrf
            @method('PUT') <!-- Include this to change the method to PUT -->

            <div class="form-group mb-3">
                <label for="first_name">First Names</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
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
            <div class="form-group mb-3">
                <label for="country_code">Country Code</label>
                <select name="country_code" class="form-control">
                    <option value="+1" {{ $user->country_code == '+1' ? 'selected' : '' }}>(+1) US</option>
                    <option value="+92" {{ $user->country_code == '+92' ? 'selected' : '' }}>(+92) Pakistan</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="phone_number">Phone Number</label>
                <input type="tel" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required>
            </div>
            @if($role == 'Donor')
                <div class="form-group  mb-3">
                    <label for="company_name">Company Name</label>
                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $user->company_name) }}" required>
                </div>
                <div class="form-group  mb-3">
                    <label for="company_website">Company Website</label>
                    <input type="url" class="form-control" id="company_website" name="company_website" value="{{ old('company_website', $user->company_website) }}">
                </div>
                <div class="form-group  mb-3 full-width">
                    <label for="company_address">Company Address</label>
                    <input type="text" class="form-control" id="company_address" name="company_address" value="{{ old('company_address'), $user->company_address }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
            @endif
            <button type="submit" class="btn btn-primary">Update</button>
        </form>

    </div>
@endsection
    