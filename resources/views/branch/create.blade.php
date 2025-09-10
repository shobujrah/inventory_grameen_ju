@extends('layouts.master')
@section('content')

    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Create Branch</h3>
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
                    <div class="card comman-shadow">
                        <div class="card-body">
                                   

                            <div class="row">
                                <form action="{{ route('branch.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3 col-md-12">
                                        <h5 class="form-title branch-info" > Branch Information </h5>
                                        <hr>
                                    </div>

                                    <div class="mb-3 col-md-8">
                                        <label for="name" class="form-label">Name:</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>

                                    <div class="mb-3 col-md-8">
                                        <label for="mobile_no" class="form-label">Mobile No:</label>
                                        <input type="tel" class="form-control" id="mobile_no" name="mobile_no" required>
                                    </div>

                                    <div class="mb-3 col-md-8">
                                        <label for="email" class="form-label">Email Address:</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>


                                    <div class="mb-3 col-md-8">
                                        <label for="name" class="form-label">Address:</label>
                                        <input type="text" class="form-control" id="address" name="address" required>
                                    </div>

                                    <div class="mb-3 col-md-8">
                                        <label for="type" class="form-label">Type:</label>
                                        <select class="form-control select2" id="type" name="type" required>
                                            <option value="">--Select Type--</option>
                                            <option value="Headoffice">Headoffice</option>
                                            <option value="Warehouse">Warehouse</option>
                                            <option value="Branch">Branch</option>
                                        </select>
                                    </div>

                                    <a href="{{ route('branch.create') }}" class="btn btn-info">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>

                                </form>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection



@section('script')

<script>
    $('.select2').select2();
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