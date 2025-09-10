@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Settings</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('setting.page') }}">Settings</a></li>
                            <li class="breadcrumb-item active">General Settings</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="settings-menu-links">
                <ul class="nav nav-tabs menu-tabs">
                    <li class="nav-item active">
                        <a class="nav-link active">General Settings</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="#">Others</a>
                    </li> -->
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Website Basic Details</h5>
                        </div>
                        <div class="card-body pt-0">
                            <form action="{{ route('setting.page.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <div class="settings-form">
                                    <div class="form-group">
                                        <label>Website Name <span class="star-red">*</span></label>
                                        <input type="text" class="form-control" name="website_name" placeholder="Enter Website Name" required  value="{{ $settings->website_name ?? '' }}">
                                    </div>

                                    <!-- <div class="form-group">
                                        <label>Logo<span class="star-red">(.jpeg,.png,.jpg)*</span></label>
                                        <input type="file" id="logo" name="logo" class="form-control" accept=".jpeg,.png,.jpg" required>

                                            <img src="{{ asset('logo/' . $settings->logo) }}" alt="logo" class="img-thumbnail mt-2" width="100">
                                    </div> -->


                                    <div class="form-group">
                                        <label>Logo<span class="star-red">(.jpeg,.png,.jpg)*</span></label>
                                        <input type="file" id="logo" name="logo" class="form-control" accept=".jpeg,.png,.jpg"
                                            {{ empty($settings->logo) ? 'required' : '' }}>
                                        
                                        @if(!empty($settings->logo))
                                            <img src="{{ asset('logo/' . $settings->logo) }}" alt="logo" class="img-thumbnail mt-2" width="100">
                                        @endif
                                    </div>


                                    <div class="form-group mb-0">
                                        <div class="settings-btns">
                                            <button type="submit" class="btn btn-orange">Update</button>
                                            <!-- <button type="submit" class="btn btn-grey">Cancel</button> -->
                                            <button type="button" class="btn btn-grey" onclick="window.location='{{ route('setting.page') }}'">Cancel</button>
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