@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Stock</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('stock.list') }}">Stock</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- message --}}
            {!! Toastr::message() !!}

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table comman-shadow">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                    <thead class="warehouse-thread">
                                        <tr>
                                            <th>Serial</th>
                                            <th>Branch Name</th>
                                            <th>Type</th>
                                            <th>Stock</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @php
                                            $totalStock = 0;
                                        @endphp

                                        @foreach ($requisitions as $requisition)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $requisition->branch->name ?? '' }}</td>
                                                <!-- <td>{{ $requisition->branch->type ?? '' }}</td> -->
                                                <td><span class="badge badge-primary">{{ $requisition->branch->type ?? '' }}</span></td>
                                                <td>{{ $requisition->total_stock }}</td>
                                                <td>
                                                    <a href="{{ route('stock.view', $requisition->branch_id) }}"
                                                        class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                        title="{{ __('View') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>

                                            </tr>
                                            @php
                                                $totalStock += $requisition->total_stock;
                                            @endphp

                                        @endforeach
                                    </tbody>
                                        @if($showTotalStock)
                                            <tr>
                                                <td colspan="3" class="text-end"><b>Total Stock of all Branch:</b></td>
                                                <td><b>{{ $totalStock }}</b></td>
                                            </tr>
                                        @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
