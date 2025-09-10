
@extends('layouts.master')

@section('content')


<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Unverify Users</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Unverify Users</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {!! Toastr::message() !!}





        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body row">

                        </br>

                        <div class="table-responsive">
                            <table class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                <thead class="user-thread">
                                    <tr>
                                        <th>Serial</th>
                                        <th>User ID</th>
                                        <th>Profile</th>
                                        <th>User Name</th>
                                        <th>Branch Name</th>
                                        <th>Branch Type</th>
                                        <th>Role</th>
                                        <th>Position</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Date Join</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                        @foreach ($unverifyuser as $unverifyusers)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $unverifyusers->user_id }}</td>
                                                <td>
                                                    @if($unverifyusers->avatar)
                                                    <img src="{{ asset('images/' . $unverifyusers->avatar) }}" alt="" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        No Image
                                                    @endif
                                                </td>
                                                <td>{{ $unverifyusers->name }}</td>
                                                <td>{{ $unverifyusers->branch_name }}</td>
                                                <td>{{ $unverifyusers->branch_type }}</td>

                                                <!-- <td>{{ $unverifyusers->role_name }}</td> -->

                                                <td>
                                                    @if($unverifyusers->role_name)
                                                        <span class="badge" style="background-color: green; color: white;">
                                                            {{ $unverifyusers->role_name }}
                                                        </span>
                                                    @else
                                                        <span class="badge" style="background-color: gray; color: white;">
                                                            Not Assigned Role
                                                        </span>
                                                    @endif
                                                </td>

                                                <td>{{ $unverifyusers->position }}</td>
                                                <td>{{ $unverifyusers->email }}</td>
                                                <td>{{ $unverifyusers->phone_number }}</td>
                                                <td>{{ $unverifyusers->join_date }}</td>
                                                <td>
                                                    @if($unverifyusers->status == 'Active')
                                                        <span class="badge bg-success">{{ $unverifyusers->status }}</span>
                                                    @elseif($unverifyusers->status == 'Inactive')
                                                        <span class="badge bg-warning">{{ $unverifyusers->status }}</span>
                                                    @else($unverifyusers->status == 'Disable')
                                                        <span class="badge bg-danger">{{ $unverifyusers->status }}</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    
                                                    <!-- <a href=""
                                                        class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                        title="{{ __('Verify') }}">
                                                        <i class="fas fa-user-shield"></i>
                                                    </a> -->

                                                    <!-- <a href="javascript:void(0)" class="btn btn-sm align-items-center verify-user-btn" 
                                                        data-id="{{ $unverifyusers->id }}" data-bs-toggle="tooltip" title="{{ __('Verify') }}">
                                                        <i class="fas fa-user-shield"></i>
                                                    </a> --> 


                                                    @if($unverifyusers->status == 'Active')
                                                        <a href="javascript:void(0)" class="btn btn-sm align-items-center verify-user-btn" 
                                                        data-id="{{ $unverifyusers->id }}" data-bs-toggle="tooltip" title="{{ __('Verify') }}">
                                                        <i class="fas fa-user-shield"></i>
                                                        </a>
                                                    @endif


                                                    <a href="{{ route('unverify.user.edit', $unverifyusers->id) }}"
                                                        class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                        title="{{ __('Edit') }}">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                    <a href="{{ route('unverify.user.delete', $unverifyusers->id) }}"
                                                        class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                        title="{{ __('Delete') }}">
                                                        <i class="fas fa-trash text-danger"></i>
                                                    </a>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                            </table>

                        </div>


                        <div class="modal fade" id="verifyUserModal" tabindex="-1" aria-labelledby="verifyUserModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="verifyUserModalLabel">Verify User</h3>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to verify this user?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" id="confirmVerifyBtn">Yes</button>
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

    </div>
</div>





<script>
    $(document).ready(function() {
        var userId; 

        $('.verify-user-btn').on('click', function() {
            userId = $(this).data('id'); 
            $('#verifyUserModal').modal('show'); 
        });


        $('#confirmVerifyBtn').on('click', function() {
            if (userId) {
                $.ajax({
                    url: '{{ route('verify.user') }}', 
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', 
                        user_id: userId
                    },
                    success: function(response) {
                        if (response.success) {

                            location.reload(); 
                            
                            toastr.success('User Verified Successfully.');
                            
                        } else {
                            toastr.error('Failed to verify this user. Assign the role to this user, then verify it.');
                        }
                    }
                });
            }
        });
    });
</script>




@endsection
