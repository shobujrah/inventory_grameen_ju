
@extends('layouts.master')
@section('content')
{{-- message --}}
{!! Toastr::message() !!}
<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">List Users</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">List Users</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="student-group-form">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search by ID ...">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search by Name ...">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search by Phone ...">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="search-student-btn">
                        <button type="btn" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-stripped table table-hover table-center mb-0" id="UsersList">
                                <thead class="student-thread">
                                    <tr>
                                        <th>User ID</th>
                                        <th>Profile</th>
                                        <th>User Name</th>
                                        <th>Branch Name</th>
                                        <th>Branch Type</th>

                                        <th>Role</th>

                                        <!-- <th>Position</th> -->
                                         
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Date Join</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- model delete --}}
<div class="modal custom-modal fade" id="delete" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Delete User</h3>
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <form action="{{ route('user/delete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" class="e_user_id" value="">
                            <input type="hidden" name="avatar" class="e_avatar" value= "">
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary paid-continue-btn" style="width: 100%;">Delete</button>
                                </div>
                                <div class="col-6">
                                    <a data-bs-dismiss="modal"
                                        class="btn btn-primary paid-cancel-btn">Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 



{{-- Password Reset Modal --}}
<div class="modal custom-modal fade" id="password_reset_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Reset Password</h3>
                </div>
                <form action="{{ route('users.reset-password') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" class="reset_user_id" value="">

                    <div class="form-group">
                        <label>Reset Password</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" name="new_password_confirmation" required>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <a data-bs-dismiss="modal" class="btn btn-secondary" style="width: 100%;">Cancel</a>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<style>
    .radio.inline,.checkbox.inline{display:inline-block;padding-top:5px;margin-bottom:0;vertical-align:middle;}
</style>
{{-- Update page permission modal --}}
<div class="modal custom-modal fade" id="update_permission_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <!-- <h3>Delete role</h3>
                    <p>Are you sure want to delete?</p> -->
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                    
                        {{-- <form action="{{ route('role/update', $role->id) }}" method="POST"> --}}
                        {{--
                        <form action="{{ route('role/update', $role->id) }}" method="POST">
                            @method('PUT')
                                @csrf
                                 --}}
                                <!-- A -->
                                <div class="row-fluid">

                                    <!-- <h4>Page Permission:</h4> -->
                                    <h5>Page Permission :</h5>
                                    <div id="res_type"></div>
                                   
                                </div>
                                
                                <br>
                                <!-- A -->
                        {{--
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary paid-continue-btn" style="width: 100%;">Save</button>
                                    </div>
                                    <div class="col-6">
                                        <a data-bs-dismiss="modal"
                                            class="btn btn-primary paid-cancel-btn">Cancel
                                        </a>
                                    </div>
                                </div>
                        --}}
                            <!-- Time to play  -->
                            
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@section('script')
{{-- delete js --}}
<script>
    $(document).on('click','.delete',function()
    {
        var _this = $(this).parents('tr');
        $('.e_user_id').val(_this.find('.user_id').data('user_id'));
        $('.e_avatar').val(_this.find('.avatar').data('avatar'));
    });
</script>

{{-- userwise page permission js --}}
<script>
    // X $(document).on('click','.update_permission',function()
    // {
    //     var _this = $(this).parents('tr');
    //     var user_pk_id = $('.e_user_id').val(_this.find('.user_id').data('user_id'));
    // }); X
    // custom starts
    // edit employee ajax request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
        // A
        // $.ajax({
        //   url: '{{-- {{ route('edit') }} --}}',
        //   method: 'get',
        //   data: {
        //     id: id,
        //     _token: '{{ csrf_token() }}'
        //   },
        //   success: function(response) {
        //     $("#fname").val(response.first_name);
        //     $("#lname").val(response.last_name);
        //     $("#email").val(response.email);
        //     $("#phone").val(response.phone);
        //     $("#post").val(response.post);
        //     $("#avatar").html(
        //       `<img src="storage/images/${response.avatar}" width="100" class="img-fluid img-thumbnail">`);
        //     $("#emp_id").val(response.id);
        //     $("#emp_avatar").val(response.avatar);
        //   }
        // });
        // A

        $(document).on('click', '.update_permission', function(e) {
            e.preventDefault();
            var _this = $(this).parents('tr');
            var user_pk_id = _this.find('.user_id').data('user_id'); // Get the value, not the jQuery object
            let csrf = '{{ csrf_token() }}';
            $.ajax({
                url: '{{ route('page/getPagePermission') }}',
                method: 'POST',
                data: {
                    id: user_pk_id, // Pass the actual value
                    _token: csrf, // Use the CSRF token you've defined
                },
                dataType: 'html',
                success: function(result) {
                    $("#res_type").html(result);
                }
            });
        });

    //   });
    // custom ends
</script>

{{-- get user all js --}}
<script type="text/javascript">
    $(document).ready(function() {
       $('#UsersList').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            ajax: {
                url:"{{ route('get-users-data') }}",
            },
            columns: [  
                {
                    data: 'user_id',
                    name: 'user_id',
                },
                {
                    data: 'avatar',
                    name: 'avatar'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'branch_name',
                    name: 'branch_name'
                },
                {
                    data: 'branch_type',
                    name: 'branch_type'
                },



                {
                    data: 'role_name',
                    name: 'role_name'
                },



                // {
                //     data: 'position',
                //     name: 'position'
                // },

                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'phone_number',
                    name: 'phone_number'
                },
                {
                    data: 'join_date',
                    name: 'join_date'
                },
                
               
                {
                    data: 'status',
                    name: 'status',
                },
                {
                    data: 'modify',
                    name: 'modify',
                },
            ]
        });
    });

    // A
    // edit employee ajax request
    // $(document).on('click', '.editIcon', function(e) {
    //     e.preventDefault();
    //     let id = $(this).attr('id');
    //     $.ajax({
    //       url: '{{-- {{ route('edit') }} --}}',
    //       method: 'get',
    //       data: {
    //         id: id,
    //         _token: '{{ csrf_token() }}'
    //       },
    //       success: function(response) {
    //         $("#fname").val(response.first_name);
    //         $("#lname").val(response.last_name);
    //         $("#email").val(response.email);
    //         $("#phone").val(response.phone);
    //         $("#post").val(response.post);
    //         $("#avatar").html(
    //           `<img src="storage/images/${response.avatar}" width="100" class="img-fluid img-thumbnail">`);
    //         $("#emp_id").val(response.id);
    //         $("#emp_avatar").val(response.avatar);
    //       }
    //     });
    //   });
    // A
</script>


<script>
    $(document).on('click', '.reset_password', function() {
        var userId = $(this).data('user_id');
        $('.reset_user_id').val(userId);
    });
</script>

@endsection 


@endsection
