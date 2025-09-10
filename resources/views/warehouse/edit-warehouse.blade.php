
@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Edit Warehouse</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('warehouse/add/page') }}">Warehouse</a></li>
                                <li class="breadcrumb-item active">Edit Warehouse</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            {{-- message --}}
            {!! Toastr::message() !!}
            <div class="row">
                <div class="col-sm-12">
                    <div class="card comman-shadow">
                        <div class="card-body">
                            <form action="{{ route('warehouse/update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" class="form-control" name="id" value="{{ $warehouseEdit->id }}" readonly>
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="form-title warehouse-info">Warehouse Information
                                            <span>
                                                <a href="javascript:;">
                                                    <!-- <i class="feather-more-vertical"></i> -->
                                                </a>
                                            </span>
                                        </h5>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Name <span class="login-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Enter Warehouse Name" value="{{ $warehouseEdit->name }}">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Tittle <span class="login-danger">*</span></label>
                                            <input type="text" class="form-control @error('tittle') is-invalid @enderror" name="tittle" placeholder="Enter Warehouse Tittle" value="{{ $warehouseEdit->tittle }}">
                                            @error('tittle')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Address </label>
                                            <input class="form-control @error('address') is-invalid @enderror" type="text" name="address" placeholder="Enter Warehouse Address" value="{{ $warehouseEdit->address }}">
                                            @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Status <span class="login-danger">*</span></label>
                                            <select class="form-control select" name="status">
                                                <option disabled>Select Status</option>
                                                <option value="Active" {{ $warehouseEdit->status == 'Active' ? "selected" :"Active" }} >Active</option>
                                                <option value="Disable" {{ $warehouseEdit->status == 'Disable' ? "selected" :"Disable" }} >Disable</option>
                                                <option value="Inactive" {{ $warehouseEdit->status == 'Inactive' ? "selected" :"Inactive" }} >Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group students-up-files">
                                            <label>Upload Warehouse Photo (150px X 150px)</label>
                                            <div class="uplod">
                                                <h2 class="table-avatar">
                                                    <a class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ Storage::url('warehouse-photos/'.$warehouseEdit->upload) }}" alt="User Image">
                                                    </a>
                                                </h2>
                                                <label class="file-upload image-upbtn mb-0 @error('upload') is-invalid @enderror">
                                                    Choose File <input type="file" name="upload">
                                                </label>
                                                <input type="hidden" name="image_hidden" value="{{ $warehouseEdit->upload }}">
                                                @error('upload')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="warehouse-submit">
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
@endsection
