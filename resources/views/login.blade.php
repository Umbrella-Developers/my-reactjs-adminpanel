@extends('layouts.appLogin')

@section('title', 'Login | Human-I-T')
@section('page_css')
    
@endsection
@section('content')
    <!-- Layout container -->
    <div class="layout-page">
        <div class="login_bg"></div>
        <!-- Content wrapper -->
        <div class="content-wrapper">
            <!-- Content -->
            <div class="login_form_wrapper container">
                <div class="login_form">
                    <div class="row g-3 mb-4">
                        <div class="col">
                            <div class="form_header">
                                <img class="header_logo" src="{{ asset('assets/img/login-logo.png') }}" alt="logo" />
                                <div class="row">
                                    <div class="col text-center">
                                        <h3>Login</h3>
                                        <h4>Let's make some real impact here.</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col">
                            <form action="auth/login" method="POST">
                                @csrf
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="email">Email</label>
                                            <input type="email" id="email" class="form-control" name="email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col text-center pb-3">
                                        <div class="forgot-password">
                                            <a href="users/forget-password">Forgot Password?</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col text-center">
                                        <button type="submit" class="btn btn-primary">Continue</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
            <!-- / Content -->

            <!-- Footer -->
            @include('partials.footer')
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
    </div>
    <!-- / Layout page -->
    
@endsection
    