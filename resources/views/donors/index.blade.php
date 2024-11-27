@extends('layouts.app')

@section('title', 'Donors')
@section('page_css')
    <link href="https://cdn.datatables.net/2.1.4/css/dataTables.bootstrap5.css" />
@endsection
@section('content')
    <!-- Include the header -->
    @include('partials.header')
    <div class="row mb-4 mt-4 pt-4">
        <div class="col text-start">
            <h3>Donors</h3>
        </div>
        <div class="col text-end">
            <a class="btn btn-primary" id="sync-donations" style="color: #fff;" data-href="{{ route('donations.getSyncPages') }}"><i class="fas fa-circle"></i> Sync Donations</a> 
            
            <a class="btn btn-primary" href="{{ route('donors.create') }}"><i class="fas fa-plus"></i> Create Donor</a>
        </div>
    </div>
    <div class="">
    <?php // json_encode($data, JSON_PRETTY_PRINT) }}</pre> ?>
        <table class="table "  id="users_table">
        <thead>
            <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Tech Donor Customer ID</th>
            <th>Role</th>
            <th>Status</th>
            <th>Sync</th>
            <th>Update</th>
            <th>Actions</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            <?php // print_r($data); exit; ?>
            @php
                $users = $data['data']['user'];

            @endphp
            @if($users->count())
                @foreach($users as $key => $user)
                    <tr>
                        <td>
                            <strong>{{$user->first_name}} {{$user->last_name}}</strong>
                        </td>
                        <td>{{$user->email}}</td>
                        <td>
                            {{ $user->tech_donor_customer_id ?? 'NULL'}}
                        </td>
                        <td>
                            {{ $user->roles[0]->name ?? ''}}
                        </td>
                        <td>
                            @if($user->is_active == 1)
                                <span class="badge bg-label-primary me-1">Active</span>
                            @else 
                                <span class="badge bg-label-secondary me-1">Disabled</span>
                            @endif
                        </td>
                        <td>
                            @if($user->tech_donor_customer_id != null)
                                <a class="btn btn-primary" href="{{ route('donations.sync', ['id' => $user->id]) }}"><i class="fas fa-circle"></i> Sync</a>
                            @endif
                        </td>
                        <td>
                            @if($user->tech_donor_customer_id != null)
                                <a class="btn btn-primary" href="{{ route('donations.update', ['id' => $user->id]) }}"><i class="fas fa-bullseye"></i> Update</a>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('donations.donor_donations', $user->id) }}">
                                    <i class="bx bx-edit-alt me-1"></i> Donations
                                </a>
                                <a class="dropdown-item" href="{{ route('donors.edit', $user->id) }}">
                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                </a>
                                <a class="dropdown-item delete_record" href="javascript:void(0);" data-id="{{ $user->id }}">
                                    <i class="bx bx-trash me-1"></i> Delete
                                </a>
                                <!-- Delete form -->
                                <form id="delete-form-{{ $user->id }}" action="{{ route('donors.destroy', $user->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <a class="dropdown-item clear_donations" href="javascript:void(0);" data-id="{{ $user->id }}">
                                    <i class="bx bx-trash me-1"></i> Clear Donations
                                </a>
                                <!-- Delete form -->
                                <form id="clear-donations-form-{{ $user->id }}" action="{{ route('donations.clear', $user->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else

            @endif
            
        </tbody>
        </table>
    </div>
        
@endsection

@section('page_js')
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.bootstrap5.js"></script>
    <script>
        new DataTable('#users_table');
        (function($){
            $(document).ready(function(){
                $(document).on('click', '.delete_record', function(){
                    let userId = $(this).attr('data-id');
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#delete-form-' + userId).submit();
                            }
                        });
                });
                $(document).on('click', '.clear_donations', function(){
                    let userId = $(this).attr('data-id');
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, clear donations!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#clear-donations-form-' + userId).submit();
                            }
                        });
                });
            });
        })(jQuery);
    
    (function($){
        $(document).ready(function() {
            $("#sync-donations").click(function (event) {
                var formData = {
                    _token: "{{ csrf_token() }}" // Include CSRF token
                };
                // Clear existing alert messages
                //$('.alert').remove();
                
                var syncDonations = function(currentPage, totalPages) {
                    if (currentPage <= totalPages) {
                        console.log('page'+currentPage);
                        $.ajax({
                            type: "GET",
                            url:  "{{ route('donations.syncAll') }}" + "?page=" + currentPage, // Append page to the URL
                            data: formData,
                            dataType: "json",
                            beforeSend: function() {
                                // Show a message or perform an action before the AJAX call
                                $("#ajax-loader").hide(); // Example: Show the loader
                                let timerInterval;
                                Swal.fire({
                                    title: "Syncing Donations...",
                                    html: "Syncing request "+currentPage + " of " + totalPages+". Please wait...",
                                    //timer: 5000,
                                    timerProgressBar: true,
                                    didOpen: () => {
                                        Swal.showLoading();
                                        const timer = Swal.getPopup().querySelector("b");
                                        timerInterval = setInterval(() => {
                                            timer.textContent = `${Swal.getTimerLeft()}`;
                                        }, 100);
                                    },
                                    willClose: () => {
                                        clearInterval(timerInterval);
                                        $("#ajax-loader").show();
                                    }
                                }).then((result) => {
                                    /* Read more about handling dismissals below */
                                    if (result.dismiss === Swal.DismissReason.timer) {
                                        console.log("I was closed by the timer");
                                    }
                                });
                            },
                            success: function (response) {
                                console.warn(response);
                                // Handle the response
                                $("#ajax-loader").hide();
                                if (response.status == true) {
                                    console.log("Syncing page " + currentPage + " of " + totalPages);
                                    
                                    // Proceed to the next page
                                    syncDonations(currentPage + 1, totalPages); // Recursive call to fetch next page
                                    
                                }
                            },
                            error: function (xhr) {
                                // Handle errors (optional, based on your needs)
                                console.log("Error on page " + currentPage + ": " + xhr.responseText);
                            }
                        });
                    } else {
                        console.log("Sync complete.");
                        $("#ajax-loader").hide(); // Hide loader when sync is done
                        $(".alert-wrapper").html(`
                            <div class="alert alert-success mt-3">
                                <ul>Donations synced successfully.</ul>
                            </div>
                        `);
                        Swal.fire({
                            //position: "top-end",
                            icon: "success",
                            title: "Donations synced successfully.",
                            showConfirmButton: false,
                            timer: 5000,
                            willClose: () => {
                                window.location.href = "/donations";
                                
                            }
                        });
                    }
                };
                $.ajax({
                    type: "GET",
                    url: $(this).attr('data-href'),
                    data: formData,
                    dataType: "json",
                    success: function (response) {
                        if(response.status == true && response.pages > 0) {
                            // Start the recursive process from page 1
                            let timerInterval;
                            Swal.fire({
                                title: "Please wait!",
                                html: "Calculating number of requests...",
                                timer: 2000,
                                timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                                const timer = Swal.getPopup().querySelector("b");
                                timerInterval = setInterval(() => {
                                timer.textContent = `${Swal.getTimerLeft()}`;
                                }, 100);
                            },
                            willClose: () => {
                                clearInterval(timerInterval);
                                $("#ajax-loader").show();
                            }
                            }).then((result) => {
                                /* Read more about handling dismissals below */
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    console.log("I was closed by the timer");
                                }
                            });
                            syncDonations(1, response.pages);
                        }
                    },
                    error: function (xhr) {
                        console.log('errors');
                        // Display validation error messages (from Laravel)
                        // let errorMessages = '';
                        // $.each(xhr.responseJSON.errors, function(key, value) {
                        //     errorMessages += `<li>${value}</li>`;
                        // });

                        // $(".alert-wrapper").before(`
                        //     <div class="alert alert-danger mt-3">
                        //         <ul>The two-factor code is invalid or has expired.</ul>
                        //     </div>
                        // `);

                        // // Hide alert after 3 seconds
                        // setTimeout(function() {
                        //         $(".alert-danger").fadeOut(); // You can also use .remove() if you want to completely remove it
                        //     }, 3000);
                    }, 
                    complete: function(){
                        console.log('completed');
                        //$("#ajax-loader").hide();
                    }
                });
            });
            
        });
    })(jQuery);
    </script>
@endsection