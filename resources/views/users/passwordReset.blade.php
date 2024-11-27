@extends('layouts.appLogin')

@section('title', 'Login | Human-I-T')
@section('page_css')
    
@endsection
@section('content')

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
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
                                <img class="header_logo mb-3" src="{{ asset('assets/img/login-logo.png') }}" alt="logo" />
                                <div class="row">
                                    <div class="col text-center mt-3">
                                        <h3>Reset Password</h3>
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
                            <form id="verifyUserEmail" action="/user/reset-password" method="POST">
                                @csrf
                                <div class="row g-3 mb-3">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="password">Password</label>
                                            <input type="password" id="password" class="form-control" name="password" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="c_password">Confirm Password</label>
                                            <input type="password" id="c_password" class="form-control" name="c_password" required>
                                        </div>
                                    </div>
                                </div>
                                <div id="div-alert" class=" mb-3"></div>
                                <div class="row g-3">
                                    <div class="col text-center">
                                        <button type="submit" class="btn btn-primary">Update</button>
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

@section('page_js')
<script type="text/javascript">
    $(document).ready(function () {
        $("#verifyUserEmail").submit(function (event) {
            event.preventDefault(); // Prevent default form submission

            var formData = {
                secret_token: "{{ $token }}",
                password: $('#password').val(),
                c_password: $('#c_password').val(),
                _token: "{{ csrf_token() }}" // Include CSRF token
            };

            // Clear existing alert messages
            $('.alert').remove();
            $("#ajax-loader").show();

            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: formData,
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        // Append success alert
                        $("#div-alert").before(`
                            <div class="alert alert-success">
                                ${response.message}
                            </div>
                        `);

                        // Hide alert after 3 seconds
                        setTimeout(function() {
                            $(".alert-success").fadeOut(); // You can also use .remove() if you want to completely remove it
                        }, 3000);
                        // Redirect to the donations page after a short delay
                        setTimeout(function() {
                            window.location.href = response.redirect_url;
                        }, 2000); // Redirect after 2 seconds
                    } else {
                        
                        // Append error alert (just in case)
                        $("#div-alert").before(`
                            <div class="alert alert-danger">
                                ${response.message}
                            </div>
                        `);

                        // Hide alert after 3 seconds
                        setTimeout(function() {
                            $(".alert-danger").fadeOut(); // You can also use .remove() if you want to completely remove it
                        }, 3000);
                    }
                },
                error: function(){
                    $("#div-alert").before(`
                        <div class="alert alert-danger">
                            Something went wrong. Please try again later.
                        </div>
                    `);

                    // Hide alert after 3 seconds
                    setTimeout(function() {
                        $(".alert-danger").fadeOut(); // You can also use .remove() if you want to completely remove it
                    }, 3000);
                },
                complete: function(){
                   $("#ajax-loader").hide();
                }
            });
        });
    });
</script>
@endsection
 