
@extends('layouts.master')

@section('content')

    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Branch View</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active"> <a href="{{ route('branch.list') }}">Branch</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card comman-shadow">
                        <div class="card-body">

                            <div class="row">

                                    <div class="mb-3 col-md-12">
                                        <h5 class="form-title branch-info" > Branch Information </h5>
                                        <hr>
                                    </div>
                                    
                                    <div class="mb-2 col-md-8">
                                        <label for="name">Name: {{ $branch->name }}</label>
                                        <p></p>
                                    </div>

                                    <div class="mb-2 col-md-8">
                                        <label for="address">Address: {{ $branch->address }}</label>
                                        <p></p>
                                    </div>

                                    <div class="mb-2 col-md-8">
                                        <label for="type">Type: {{ $branch->type }}</label>
                                        <p></p>
                                    </div>
                                    <div class="mb-2 col-md-8">
                                        <label for="type">Mobile: {{ $branch->mobile_no }}</label>
                                        <p></p>
                                    </div>
                                    <div class="mb-2 col-md-8">
                                        <label for="type">Email: {{ $branch->email }}</label>
                                        <p></p>
                                    </div>

                                    <div class="mb-3 col-md-8">
                                        <a href="{{ route('branch.list') }}" class="btn btn-info">Back</a>
                                    </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
