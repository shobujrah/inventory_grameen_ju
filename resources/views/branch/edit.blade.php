@extends('layouts.master')
@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Edit Branch</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('branch.list') }}">Branch</a></li>
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
                        <form action="{{ route('branch.update', $branch->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3 col-md-12">
                                <h5 class="form-title branch-info">Branch Information</h5>
                                <hr>
                            </div>

                            <div class="mb-3 col-md-8">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $branch->name }}" required>
                            </div>

                            <div class="mb-3 col-md-8">
                                <label for="address" class="form-label">Address:</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ $branch->address }}" required>
                            </div>

                            <div class="mb-3 col-md-8">
                                <label for="mobile_no" class="form-label">Phone:</label>
                                <input type="tel" class="form-control" id="mobile_no" name="mobile_no" value="{{ $branch->mobile_no }}" required>
                            </div>
                            <div class="mb-3 col-md-8">
                                <label for="email" class="form-label">Email:</label>
                                <input type="text" class="form-control" id="email" name="email" value="{{ $branch->email }}" required>
                            </div>

                            <!-- <div class="mb-3 col-md-8">
                                <label for="type" class="form-label">Type:</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="">--Select Type--</option>
                                    <option value="Headoffice" {{ $branch->type == 'Headoffice' ? 'selected' : '' }}>Headoffice</option>
                                    <option value="Warehouse" {{ $branch->type == 'Warehouse' ? 'selected' : '' }}>Warehouse</option>
                                </select>
                            </div> -->

                            <div class="mb-3 col-md-8">
                                <label for="type" class="form-label">Type:</label>
                                <select class="form-control select2" id="type" name="type" required>
                                    <option value="">--Select Type--</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->type }}" {{ $branch->type == $type->type ? 'selected' : '' }}>
                                            {{ $type->type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <a href="{{ route('branch.list') }}" class="btn btn-info">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update</button>

                        </form>
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
