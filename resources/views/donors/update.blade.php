@extends('layouts.app')

@section('title', 'Update Donor')
@section('page_css')
    
@endsection
@section('content')
    <!-- Include the header -->
    @include('partials.header')
    @php
        $user = $data['data']['user'];
        @endphp
    <div class="row mb-4 mt-4 pt-4">
        <div class="col text-start">
            <div class="d-flex gap-7 align-items-center">
                <div class="symbol symbol-100px">
                    <img src="{{getAvatar($user)}}" class="img-thumbnail img-fluid" alt="{{$user->first_name}}">
                </div>
                <div class="d-flex flex-column gap-2">
                    <h3 class="mb-0">{{ucfirst($user->first_name)}} {{ucfirst($user->last_name)}} <span class="fs-7">( {{$user->roles()->first()->name ?? "Admin"}} )</span></h3>
                    @if($user->company_name)
                        <h4 class="mb-0">{{ucfirst($user->company_name)}} </h4>
                    @endif
                    
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa fa-envelope fs-5"></i>
                        <a href="mailto:{{$user->email}}" class="text-muted text-hover-primary">{{$user->email}}</a>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="col text-end">
            <a class="btn btn-primary" href="{{ route('donors.index') }}"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="user_create">
        
        
        <form action="{{ route('donors.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        
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
                <label for="tech_donor_customer_id">Tech Donor Customer ID</label>
                <input type="text" class="form-control" id="tech_donor_customer_id" name="tech_donor_customer_id" value="{{ old('tech_donor_customer_id', $user->tech_donor_customer_id) }}" disabled>
            </div>
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}" placeholder="Leave blank to keep the current password">
            </div>
            <div class="form-group mb-3">
                <label for="c_password">Confirm Password</label>
                <input type="password" class="form-control" id="c_password" name="c_password" value="{{ old('c_password') }}" placeholder="Leave blank to keep the current password">
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
            
            <div class="form-group  mb-3">
                <label for="company_name">Company Name</label>
                <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $user->company_name) }}" required>
            </div>
            <div class="form-group  mb-3">
                <label for="company_website">Company Website</label>
                <input type="url" class="form-control" id="company_website" name="company_website" value="{{ old('company_website', $user->company_website) }}" >
            </div>
            <div class="form-group  mb-3 full-width">
                <label for="company_address">Company Address</label>
                <input type="text" class="form-control" id="company_address" name="company_address" value="{{ old('company_address', $user->company_address) }}" required>
            </div>
            <div class="form-group  mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ $user->is_active == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Account Status</label>
                </div>
            </div>
            <input type="hidden" name="role_id" value="29">
            <button type="submit" class="btn btn-primary">Update</button>
        </form>

    </div>
@endsection
@section('page_js')

<script >
    

</script>
@endsection