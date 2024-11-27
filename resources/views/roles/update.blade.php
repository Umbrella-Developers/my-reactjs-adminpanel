@extends('layouts.app')

@section('title', 'Edit Role')
@section('page_css')
    <link href="https://cdn.datatables.net/2.1.4/css/dataTables.bootstrap5.css" />
    <style>
        .accordion-button {
            background-color: #f8f9fa;
        }
        .accordion-button:not(.collapsed) {
            color: #0d6efd;
            background-color: #e2e6ea;
        }
        .form-check-label {
            margin-left: 10px;
        }
        .select-all {
            cursor: pointer;
            font-size: 0.875rem;
            text-decoration: underline;
            color: #007bff;
        }
        .select-all:hover {
            color: #0056b3;
        }
        .global-select-all {
            margin-bottom: 20px;
            cursor: pointer;
        }
        .disabled-permission {
            opacity: 0.7;
            pointer-events: none;
        }
        .disabled-permission .form-check-label {
            color: #343a40;
        }
        .disabled-permission input {
            background-color: #dee2e6;
        }
        .hidden {
            display: none;
        }
    </style>
@endsection

@section('content')
    @php
        $role = $data['role'];
        $groupedPermissions = $data['groupedPermissions'];
        $totalpermissionsCount = $data['totalpermissionsCount'];
    @endphp
    @include('partials.header')
    <div class="row mb-4 mt-4 pt-4">
        <div class="col text-start">
            <h3>Edit Role</h3>
        </div>
        <div class="col text-end">
            <a class="btn btn-primary" href="{{ route('roles.index') }}">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="user_create">
        <form action="{{ route('roles.update', $role->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Use PUT method for updating -->

            <!-- Role name input -->
            <div class="form-group mb-3">
                <label for="name">Role Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $role->name) }}" required>
            </div>
            <!-- Global Select All/Deselect All -->
            <div class="form-group mb-3">
                <input {{$totalpermissionsCount == true ? 'checked' : ''}} onclick="countCheckboxes(this);" type="checkbox" id="globalSelectAll" class="form-check-input">
                <label class="form-check-label" for="globalSelectAll">Select All Permissions</label>
            </div>
            <!-- Permissions List -->
            <div class="accordion" id="permissionsAccordion">
                <div class="row">
                    @foreach($groupedPermissions as $entity => $permissions)
                        <div class="col-md-4 col-sm-12">
                            <h5 class="global-select-all" style="background: #f8f9fa;  padding: 10px">{{ ucfirst($entity) }} Permissions</h5>
                            <div class="accordion-body">
                                @php
                                    // Define permission groups
                                    $viewGroup = ['index', 'show'];
                                    $storeGroup = ['create', 'store'];
                                    $updateGroup = ['edit', 'update'];
                                @endphp

                                <!-- Handle View Group -->
                                @if(collect($permissions)->contains(function($permission) use ($entity) {
                                    $parts = explode('-', $permission->name);
                                    return count($parts) > 1 && in_array($parts[1], ['index', 'show']);
                                }))
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="view_{{ $entity }}"
                                            onclick="togglePermissions(['{{ $entity }}-index', '{{ $entity }}-show'], this)"
                                            {{ $role->permissions->contains('name', $entity . '-index') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="view_{{ $entity }}">
                                            View {{ ucfirst($entity) }}
                                        </label>
                                    </div>
                                @endif

                                <!-- Handle Store Group -->
                                @if(collect($permissions)->contains(function($permission) use ($entity) {
                                    $parts = explode('-', $permission->name);
                                    return count($parts) > 1 && in_array($parts[1], ['create', 'store']);
                                }))
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="store_{{ $entity }}"
                                            onclick="togglePermissions(['{{ $entity }}-create', '{{ $entity }}-store'], this)"
                                            {{ $role->permissions->contains('name', $entity . '-create') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="store_{{ $entity }}">
                                            Store {{ ucfirst($entity) }}
                                        </label>
                                    </div>
                                @endif

                                <!-- Handle Update Group -->
                                @if(collect($permissions)->contains(function($permission) use ($entity) {
                                    $parts = explode('-', $permission->name);
                                    return count($parts) > 1 && in_array($parts[1], ['edit', 'update']);
                                }))
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="update_{{ $entity }}"
                                            onclick="togglePermissions(['{{ $entity }}-edit', '{{ $entity }}-update'], this)"
                                            {{ $role->permissions->contains('name', $entity . '-edit') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="update_{{ $entity }}">
                                            Update {{ ucfirst($entity) }}
                                        </label>
                                    </div>
                                @endif

                                <!-- Other Permissions -->
                                @foreach($permissions as $permission)
                                    @php
                                        // Explode the permission name by '-' to check the type
                                        $parts = explode('-', $permission->name);
                                        // Check if the second part (type) is one of the grouped permissions
                                        $isGrouped = isset($parts[1]) && in_array($parts[1], ['index', 'show', 'create', 'store', 'edit', 'update']);
                                    @endphp
                                
                                    <div class="form-check {{ $isGrouped ? 'd-none' : '' }}"> <!-- Hide grouped permissions -->
                                        <input onclick="selectAllUpdat(this);" type="checkbox" class="form-check-input" name="permissions[]"
                                            value="{{ $permission->name }}"
                                            id="permission_{{ $permission->name }}"
                                            {{ $role->permissions->contains('name', $permission->name) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="permission_{{ $permission->name }}">
                                            {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Submit button -->
            <button type="submit" class="btn btn-primary mt-4">Update Role</button>
        </form>
    </div>

    <script>
        // Helper function to toggle individual permissions based on group selection
        function togglePermissions(permissionIds, groupCheckbox) {
            selectAllUpdat(groupCheckbox);
            permissionIds.forEach(function(permissionId) {
                const permissionCheckbox = document.getElementById('permission_' + permissionId);
                const permissionWrapper = permissionCheckbox.closest('.form-check');
                
                if (permissionCheckbox) {
                    permissionCheckbox.checked = groupCheckbox.checked;
                    if (groupCheckbox.checked) {
                        permissionWrapper.classList.remove('hidden'); // Show permissions if group is checked
                    } else {
                        permissionWrapper.classList.add('hidden'); // Hide permissions if group is unchecked
                    }
                }
            });
        }

        // Handle global select all
        document.getElementById('globalSelectAll').addEventListener('change', function() {
            const isChecked = this.checked;
            const permissionCheckboxes = document.querySelectorAll('.form-check-input');

            permissionCheckboxes.forEach(function(checkbox) {
                checkbox.checked = isChecked;
            });
        });

        // Collapse All Functionality
        document.getElementById('collapseAll').addEventListener('click', function() {
            const accordions = document.querySelectorAll('.accordion-collapse');
            accordions.forEach(function(accordion) {
                accordion.classList.remove('show'); // Collapse all
            });
        });

        // Expand All Functionality
        document.getElementById('expandAll').addEventListener('click', function() {
            const accordions = document.querySelectorAll('.accordion-collapse');
            accordions.forEach(function(accordion) {
                accordion.classList.add('show'); // Expand all
            });
        });
        
        function toggleGroupPermissions(entity, groupCheckbox) {
            const permissionCheckboxes = document.querySelectorAll(`#collapse-${entity} .form-check-input`);

            permissionCheckboxes.forEach(function(checkbox) {
                checkbox.checked = groupCheckbox.checked; // Set the checkbox status based on the group checkbox
                
            });
        }

        function preventClose(event) {
            // Prevent default action to close the accordion
            event.preventDefault();
        }

        var count = 0;
        function selectAllUpdat(checkbox){
            console.log('checkbox', checkbox.id);
            var isChecked = checkbox.checked;
            var global_checkboxes = false;
            if(isChecked){
                // Select all checkbox elements
                var checkboxes = document.querySelectorAll('input[type="checkbox"]');
                console.log('checkboxes', checkboxes);
                // Get the number of checkboxes
                count = checkboxes.length;
                // Output the count
                console.log("Number of checkboxes: " + count); 
                // Check if each checkbox is checked
                var i = 1;
                checkboxes.forEach(function(checkbox) {
                    var isChecked = checkbox.checked;
                    if(checkbox.checked){
                        //console.log(i++);
                        if(i == count){
                            document.getElementById('globalSelectAll').checked = true;
                        }
                    }
                });
            }else{
                document.getElementById('globalSelectAll').checked = false;
            }
        }

        function countCheckboxes(checkbox) {
            if(checkbox.checked){
                // Select all checkbox elements
                var checkboxes = document.querySelectorAll('input[type="checkbox"]');
                // Get the number of checkboxes
                count = checkboxes.length;
                // Output the count
                console.log("Number of checkboxes: " + count);   
            }
        }
    </script>
@endsection
