@extends('layouts.app')

@section('title', 'Staff Members')
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
            <a class="btn btn-primary" href="{{ route('users.create') }}"><i class="fas fa-plus"></i> Create</a>
        </div>
    </div>
    <div class="">
    <?php // json_encode($data, JSON_PRETTY_PRINT) }}</pre> ?>
        <table class="table "  id="users_table">
        <thead>
            <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
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
                            <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                </a>
                                <a class="dropdown-item delete_record" href="javascript:void(0);" data-id="{{ $user->id }}">
                                    <i class="bx bx-trash me-1"></i> Delete
                                </a>
                                <!-- Delete form -->
                                <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
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
            });
        })(jQuery);
    </script>
@endsection