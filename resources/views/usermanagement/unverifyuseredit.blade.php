@extends('layouts.master')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .form-check-label {
        text-transform: capitalize;
    }
</style>

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Edit Unverify User</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Users</a></li>
                            <li class="breadcrumb-item active">Edit Unverify User</li>
                        </ul>
                    </div>
                </div>
            </div>
            {{-- message --}}
            {!! Toastr::message() !!}
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('unverify.user.update', $users->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="form-title"><span>Edit User</span></h5>
                                    </div>
                                    <input type="hidden" name="user_pk" value="{{ $users->id }}">
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Name <span class="login-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" value="{{ $users->name }}" required>
                                            <input type="hidden" class="form-control" name="user_id" value="{{ $users->user_id }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Email <span class="login-danger">*</span></label>
                                            <input type="text" class="form-control" name="email" value="{{ $users->email }}" required> 
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Phone Number <span class="login-danger">*</span></label>
                                            <input type="number" class="form-control" name="phone_number" value="{{ $users->phone_number }}" required>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Designation <span class="login-danger">*</span></label>
                                            <input type="text" class="form-control" name="position" value="{{ $users->position }}" required>
                                        </div>
                                    </div>


                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label for="branch_type">Branch Type <span class="login-danger">*</span></label>
                                            <select class="form-control select2" name="branch_type" id="branch_type" required>
                                                <option disabled>--Select Branch Type--</option>
                                                @foreach($branches->unique('type') as $branch)
                                                    <option value="{{ $branch->type }}" {{ $users->branch_type == $branch->type ? 'selected' : '' }}>
                                                        {{ $branch->type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('branch_type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                        <div class="col-12 col-sm-4">
                                            <div class="form-group local-forms">
                                                <label for="branch_id">Branch <span class="login-danger">*</span></label>
                                                <select class="form-control select2" name="branch_id" id="branch_id" required>
                                                    <option disabled>--Select Branch Name--</option>
                                                    @foreach($branches as $branch)
                                                        <option value="{{ $branch->id }}" data-type="{{ $branch->type }}" {{ $users->branch_id == $branch->id ? 'selected' : '' }}>
                                                            {{ $branch->name }} - ({{ $branch->type }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('branch_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>


                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Status <span class="login-danger">*</span></label>
                                            <select class="form-control select2" name="status" required>
                                                <option disabled>Select Status</option>
                                                <option value="Active" {{ $users->status == 'Active' ? 'selected' : '' }}>Active</option>
                                                <option value="Disable" {{ $users->status == 'Disable' ? 'selected' : '' }}>Disable</option>
                                                <option value="Inactive" {{ $users->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Role Name</label>
                                            <select name="roles[]" id="roles" class="form-control select2">
                                                <option value="">--Select Role Type--</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}" 
                                                        {{ $users->role_name == $role->name ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>




                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Date Of Birth <span class="login-danger">*</span></label>
                                            <input type="date" class="form-control" name="date_of_birth" value="{{ $users->date_of_birth }}" required>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Updated Date <span class="login-danger">*</span></label>
                                            <input type="text" class="form-control" name="updated_at" value="{{ $users->updated_at }}" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Address</label>
                                            <textarea name="address" id="" class="form-control" cols="0" rows="0">{{ $users->address }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="user-submit">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<style>
    .selectable {
        font-weight: bold; 
        background-color: #007bff;
        color: white;
    }
    #branch_id option:disabled {
        color: #ccc; 
    }
</style>


<script>
    $(document).ready(function() {
        $('.select2').select2();

        function filterBranches() {
            var selectedType = $('#branch_type').val();
            var branchOptions = $('#branch_id option');

            branchOptions.each(function() {
                if ($(this).data('type') === selectedType) {
                    $(this).prop('disabled', false); 
                } else {
                    $(this).prop('disabled', true);
                }
            });

            var selectedBranch = $('#branch_id').val();
            if (branchOptions.filter(':enabled[value="' + selectedBranch + '"]').length) {
                $('#branch_id').val(selectedBranch);
            } else {
                $('#branch_id').val('');
            }
            
            $('#branch_id').select2();
        }

        filterBranches();

        $('#branch_type').on('change', function() {
            filterBranches();
        });
    });
</script>


<style>
    .select2-container .select2-selection--single {
        height: 42px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 42px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px;
    }
</style>



@endsection


