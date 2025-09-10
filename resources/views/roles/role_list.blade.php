
@extends('layouts.master')
@section('content')
@section('styles')
    <!-- Start datatable css -->
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css"> -->
@endsection
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Role</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('role/list/page') }}">Role List</a></li>
                        </ul>
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
                            <input type="text" class="form-control" placeholder="Search by Class ...">
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
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5>List of Roles</h5>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('role/add/page') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- data table start -->
                            <div class="col-12 mt-5">
                                <div class="card">
                                    <div class="card-body">
                                        <!-- <h4 class="header-title float-left">Roles List</h4> -->
                                        <p class="float-right mb-2">
                                            {{-- @if (Auth::guard('admin')->user()->can('role.create')) --}}
                                                {{-- <!-- <a class="btn btn-primary text-white" href="{{ route('role/add/page') }}">Create New Role</a> --> --}}
                                            {{-- @endif --}}
                                        </p>
                                        <!-- <div class="clearfix"></div> -->
                                        <div class="data-tables">
                                            <table id="dataTable" class="text-center">
                                                <thead class="bg-light text-capitalize">
                                                    <tr>
                                                        <th>Serial</th>
                                                        <th>Name</th>
                                                        <th width="60%">Permissions</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($roles as $role)
                                                    <tr>
                                                        <td>{{ $loop->index+1 }}</td>
                                                        <td>{{ $role->name }}</td>
                                                        <td>
                                                            @foreach ($role->permissions as $perm)
                                                                <span class="badge badge-info mr-1">
                                                                    {{ $perm->name }}
                                                                </span>
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            {{-- @if (Auth::guard('admin')->user()->can('admin.edit')) --}}
                                                                <a class="btn btn-sm bg-danger-light" href="{{ url('role/edit/'.$role->id) }}">
                                                                    <i class="feather-edit"></i>
                                                                </a>
                                                                {{-- {{ url('role/delete/'.$role->id) }} Currently using it --}}
                                                                <a class="btn btn-sm bg-danger-light" href="{{ route('role.delete', $role->id) }}" disabled>
                                                                    <i class="fe fe-trash-2"></i>
                                                                </a>

                                                            {{-- @endif --}}



                                                            {{-- @if (Auth::guard('admin')->user()->can('admin.edit')) --}}
                                                            {{--
                                                                <a class="btn btn-sm bg-danger-light" href="{{ url('role/delete/'.$role->id) }}"
                                                                onclick="event.preventDefault(); document.getElementById('delete-form-{{ $role->id }}').submit();">
                                                                    <i class="feather-trash-2 me-1"></i>
                                                                </a>

                                                                <form id="delete-form-{{ $role->id }}" action="{{ url('role/delete/'.$role->id) }}" method="POST" style="display: none;">
                                                                    @method('DELETE')
                                                                    @csrf
                                                                </form>
                                                            --}}
                                                            {{-- @endif --}}

                                                            {{-- (not using this route)
                                                            <a class="btn btn-danger text-white" href="{{ route('admin.roles.destroy', $role->id) }}"
                                                            onclick="event.preventDefault(); document.getElementById('delete-form-{{ $role->id }}').submit();">
                                                                Delete
                                                            </a>

                                                            <form id="delete-form-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display: none;">
                                                                @method('DELETE')
                                                                @csrf
                                                            </form>
                                                            --}}
                                                            <!-- 1 <a class="btn btn-sm bg-danger-light delete" data-bs-toggle="modal" data-bs-target="#delete">
                                                                <i class="fe fe-trash-2"></i>
                                                            </a> -->
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- data table end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- model delete --}}
    <!-- 1 <div class="modal custom-modal fade" id="delete" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete role</h3>
                        <p>Are you sure want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            {{-- <form action="{{ route('role/delete') }}" method="POST"> --}}
                                @csrf
                                <input type="hidden" name="role_id" class="e_role_id" value="">
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
    </div> -->

    @section('script')
        {{-- delete js --}}
        <!-- 1 <script>
            $(document).on('click','.delete',function()
            {
                var _this = $(this).parents('tr');
                $('.e_role_id').val(_this.find('.role_id').text());
            });
        </script> -->
        
        <!-- Start datatable js -->
        <!-- <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
        <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script> -->
        
        <script>
            /*================================
            datatable active
            ==================================*/
            // if ($('#dataTable').length) {
            //     $('#dataTable').DataTable({
            //         responsive: true
            //     });
            // }

        </script>

    @endsection

@endsection
