@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Purchase Requisitions</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Purchase Requisition</li>
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
                                <table
                                    class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                    <thead class="warehouse-thread">
                                        <tr>
                                            <th>Serial</th>
                                            <th>Branch Name</th>
                                            <th>Project Name</th>
                                            <th>Date</th>
                                            <th>Status</th> 
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requisitions as $key => $requisition)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $requisition->branch_name }}</td>
                                                <td>{{ $requisition->project_name }}</td>
                                                <td>{{ App\Models\Utility::dateFormat($requisition->date_from) }}</td>
                                               
                                                <td>
                                                    @if ($requisition->items->where('purchase_in', 1)->count() > 0)
                                                        <span class="badge badge-info">Done</span>
                                                    @else
                                                        <span class="badge badge-warning">Pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('purchase-collection.requisition.view', $requisition->id) }}"
                                                        class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                        title="{{ __('View') }}">
                                                        <i class="fas fa-eye"></i>
                                                        
                                                    </a>

                                                 
                                                    @if ($requisition->items->where('purchase_in', 1)->count() > 0)
                                                    @else
                                                        <a href="{{ route('requisition.purchase.in', ['id' => $requisition->id]) }}"
                                                            class="btn btn-sm btn-success">In</a>


                                                    @endif
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
