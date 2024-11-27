@extends('layouts.appLogin')

@section('title', 'Login | Human-I-T')
@section('page_css')
    
@endsection
@section('content')
    @php
        $method = $method ?? '';
        $user_id = $user_id ?? null;
    @endphp
    <!-- Layout container -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

    <div class="layout-page">
        <!-- Content wrapper -->
        <div class="content-wrapper">
            <!-- Content -->
            <div class="tf_login_form_wrapper text-center mt-5 container">
                <div class="login_form mt-5">
                    <div class="row mb-4">
                        <div class="col">
                            <div class="form_header text-center">
                                <img class="user_avatar mb-3 " src="{{ getAvatar($user) }}" alt="logo" />
                                <div class="row">
                                    <div class="col text-center">
                                        <h3>Validate you device</h3>
                                        <h4>One last step - just because we really care data security.</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form_body">
                        @if($user_id)
                            <div class="row">
                                <div class="col text-center mb-3">
                                    <h5> {{ $info_text }}</h5>
                                    <h3>{{ $masked_text }}</h3>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            {{-- 
                                <div  class="col text-center">
                                    <form action="{{ route('auth.send_two_factor_code') }}" method="POST">
                                        @csrf
                                        <div class="hide d-none">
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <input type="radio" id="two_factor_method" name="two_factor_method" class="form-check-input" value="sms" checked>
                                            
                                        </div>
                                        <button type="submit" disabled class="verify_button text_me {{ $method == 'sms' ? 'active' : '' }}">Text me</button>
                                    </form>
                                </div>
                            --}}
                            {{--  
                                <div class="col text-center">
                                    <form action="{{ route('auth.send_two_factor_code') }}" method="POST">
                                        @csrf
                                        <div class="d-none hide">
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <input type="radio" id="two_factor_method" name="two_factor_method" class="form-check-input" value="email" checked>
                                        </div>
                                        <button type="submit" class="verify_button email_me {{ $method == 'email' ? 'active' : '' }}">Email me</button>
                                    </form>
                                </div>
                            --}}
                            @if($user_id)
                                <form id="twoFactorForm" action="{{ route('auth.verify_two_factor_code') }}" method="POST">
                                    @csrf
                                    <input type="hidden" id="user_id" name="user_id" value="{{ $user_id }}">
                                    <div class="form-group mb-1 mt-3">
                                        <div class="countdown_timer">
                                             If you do not receive the one-time password (OTP) via {{ $method == 'sms' ? 'text message' : 'email' }}, please use following <span id="timer_method">Resend</span> button to receive code.<br />
                                             <span id="timer" class="text-danger">60</span> seconds remaining. 
                                        </div>
                                        <!-- Resend Code Button -->
                                        <button disabled type="button" id="resendCodeBtn" class="btn btn-link">Resend Code</button>
                                    </div>
                                    <div class="form-group mb-3 mt-3">
                                        <label for="code" class="mb-2">Enter the Security Code</label>
                                        <input type="text" id="code" name="code" class="form-control text-center" required>
                                    </div>
                                    
                                    <!-- Error/Success Alert Placeholders -->
                                    <div id="alertMessages"></div>
                                    
                                    <a class="btn back_btn mr-3" href="{{ route('login') }}">Back</a> 
                                    <button type="submit" class="btn btn-primary">Continue</button>
                                </form>
                            @endif
                        </div>
                        <div id="div-alert"></div>
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
    (function($){
        $(document).ready(function() {
            var timeLeft = 60; // Total time in seconds
            var timerInterval = setInterval(function() {
                timeLeft--; // Decrease time
                $('#timer').text(timeLeft); // Update timer display

                if (timeLeft <= 0) {
                    clearInterval(timerInterval); // Stop countdown
                    $('#resendCodeBtn').prop('disabled', false);
                    $('#resendCodeBtn').show(); // Show resend button
                    $('.countdown_timer').hide(); // Show resend button
                }
            }, 1000);
        })
    })(jQuery);
    $(document).ready(function () {
        $("#twoFactorForm").submit(function (event) {
            event.preventDefault(); // Prevent default form submission

            var formData = {
                user_id: $("#user_id").val(),
                code: $("#code").val(),
                two_factor_method: $("input[name='two_factor_method']:checked").val(),
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
                                ${response.message}e
                            </div>
                        `);

                        // Hide alert after 3 seconds
                        setTimeout(function() {
                            $(".alert-danger").fadeOut(); // You can also use .remove() if you want to completely remove it
                        }, 3000);
                    }
                },
                error: function (xhr) {
                    // Display validation error messages (from Laravel)
                    let errorMessages = '';
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        errorMessages += `<li>${value}</li>`;
                    });

                    $("#div-alert").before(`
                        <div class="alert alert-danger mt-3">
                            <ul>The two-factor code is invalid or has expired.</ul>
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

        // Resend Code AJAX
        $('#resendCodeBtn').click(function () {
                $("#ajax-loader").show();   
                $.ajax({
                    type: "POST",
                    url: "{{ route('auth.resend_two_factor_code') }}", // Resend code route
                    data: {
                        user_id: $("#user_id").val(),
                        two_factor_method: $("input[name='two_factor_method']:checked").val(),
                        _token: "{{ csrf_token() }}" // CSRF token
                    },
                    success: function (response) {
                        $('#alertMessages').html(`
                            <div class="alert alert-success">
                                The code has been resent.
                            </div>
                        `);
                        // Hide alert after 3 seconds
                        setTimeout(function() {
                            $(".alert-success").fadeOut(); // You can also use .remove() if you want to completely remove it
                        }, 15000);
                    },
                    error: function () {
                        $('#alertMessages').html(`
                            <div class="alert alert-danger mt-3">
                                An error occurred while resending the code. Please try again.
                            </div>
                        `);
                        // Hide alert after 3 seconds
                        setTimeout(function() {
                            $(".alert-danger").fadeOut(); // You can also use .remove() if you want to completely remove it
                        }, 15000);
                    },
                    complete: function(){
                        $("#ajax-loader").hide();
                    }
                });
            });
    });
</script>
@endsection
 