
@extends('layouts.master')

@section('content')

    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Branch List</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active"> <a href="{{ route('branch.list') }}">Branch</a></li>
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
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="page-title">Branch Information</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('branch.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>

                            </br>

                            <div class="table-responsive">
                                <table class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                    <thead class="product-thread">
                                        <tr>
                                            <th>Serial</th>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th>Type</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($branches as $branch)
                                        <tr>
                                            <td>{{ $branch->id }}</td>
                                            <td>{{ $branch->name }}</td>
                                            <td>{{ $branch->address }}</td>
                                            <td>{{ $branch->type }}</td>
                                            <td>{{ $branch->mobile_no }}</td>
                                            <td>{{ $branch->email }}</td>

                                            <td>
                                                    <a href="{{ route('branch.show', $branch->id) }}"
                                                        class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                        title="{{ __('View') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('branch.edit', $branch->id) }}"
                                                        class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                        title="{{ __('Edit') }}">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                    <a href="{{ route('branch.delete', $branch->id) }}"
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

@endsection
