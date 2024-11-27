@extends('layouts.app')

@section('title', 'Create Donor')
@section('page_css')
    
@endsection
@section('content')
    <!-- Include the header -->
    @include('partials.header')
    <div class="row mb-4 mt-4 pt-4">
        <div class="col text-start">
            <h3>Create Donor</h3>
        </div>
        <div class="col text-end">
            <a class="btn btn-primary" href="{{ route('donors.index') }}"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="user_create">
        <form action="{{ route('donors.store') }}" method="POST" enctype="multipart/form-data">
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
                <label for="tech_donor_customer_id">Tech Donor Customer ID</label>
                <input type="text" class="form-control" id="tech_donor_customer_id" name="tech_donor_customer_id" value="{{ old('tech_donor_customer_id') }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" value="{{ old('password') }}" name="password" required>
            </div>
            <div class="form-group mb-3">
                <label for="c_password">Confirm Password</label>
                <input type="password" class="form-control" id="c_password" value="{{ old('c_password') }}" name="c_password" required>
            </div>
            <div class="form-group mb-3">
                <label for="phone_number">Phone Number</label>
                <input type="tel" id="phone_number" class="form-control" name="phone_number" value="{{ old('phone_number') }}" required>
            </div>
            {{-- <div class="form-group  mb-3">
                <label for="country_code">Country Code</label>
                <select id="country_code" name="country_code" class="form-control">
                    <option value="+1" {{ old('country_code') == '+1' ? 'selected' : '' }}>+1 US</option>
                    <option value="+92" {{ old('country_code', '+92') == '+92' ? 'selected' : '' }}>+92 Pakistan</option>
                </select>
            </div> --}}
            <div class="form-group  mb-3">
                <label for="avatar">Profile Picture</label>
                <input type="file" class="form-control" id="avatar" name="avatar">
            </div>
            
            <div class="form-group  mb-3">
                <label for="company_name">Company Name</label>
                <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name') }}" required>
            </div>
            <div class="form-group  mb-3">
                <label for="company_website">Company Website</label>
                <input type="url" class="form-control" id="company_website" name="company_website" value="{{ old('company_website') }}">
            </div>
            <div class="form-group  mb-3 full-width">
                <label for="company_address">Company Address</label>
                <input type="text" class="form-control" id="company_address" name="company_address" value="{{ old('company_address') }}" required>
            </div>
            <input type="hidden" name="is_active" value="1">
            <button type="submit" class="btn btn-primary">Create</button>
        </form>

    </div>
@endsection
    