
@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Warehouse</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('warehouse/list') }}">Warehouse</a></li>
                                <li class="breadcrumb-item active">Warehouses</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table comman-shadow">
                        <div class="card-body pb-0">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="page-title">Warehouse</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('warehouse/list') }}" class="btn btn-outline-gray me-2"><i class="feather-list"></i></a>
                                        <a href="{{ route('warehouse/grid') }}" class="btn btn-outline-gray me-2 active"><i class="feather-grid "></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="warehouse-pro-list">
                                <div class="row">
                                    @foreach ($warehouseList as $key=>$list )
                                    <div class="col-xl-3 col-lg-4 col-md-6 d-flex">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="warehouse-box flex-fill">
                                                    <div class="warehouse-img">
                                                        <a href="{{ url('warehouse/view/'.$list->id) }}">
                                                            <img class="img-fluid" alt="warehouse Info" src="{{ Storage::url('/warehouse-photos/'.$list->upload) }}" width="20%" height="20%">
                                                        </a>
                                                    </div>
                                                    <div class="warehouse-content pb-0">
                                                        <h5><a href="{{ url('warehouse/view/'.$list->id) }}">{{ $list->name }}</a></h5>
                                                        <h6>warehouse</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
