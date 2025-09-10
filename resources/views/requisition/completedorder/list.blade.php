@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Completed Order List</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('completed.order.list') }}">Completed Order List</a></li>
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
                                                <td>{{ $requisition->branch->name ?? ''}}</td>
                                                <td>{{ $requisition->project->name ?? '' }}</td>
                                                <td>{{ $requisition->date_from }}</td>
                                                <td>
                                                    @if ($requisition->status == 1 && $requisition->partial_delivery == 0)
                                                        <span class="badge" style="background-color: #ffc107; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Partial Delivered</span>

                                                    @elseif ($requisition->status == 4 && $requisition->partial_delivery == 0 && $requisition->partial_stock == NULL)
                                                        <span class="badge" style="background-color: #ffc107; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Partial Delivered</span>

                                                    @elseif ($requisition->status == 2 && $requisition->partial_delivery == 0 && $requisition->partial_stock == NULL)
                                                        <span class="badge" style="background-color: #ffc107; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Partial Delivered</span>

                                                    @elseif ($requisition->status == 1 && $requisition->partial_delivery == 1)
                                                        <span class="badge" style="background-color: #28a745; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Delivered</span>

                                                    @elseif ($requisition->status == 3 && $requisition->partial_stock == 0)
                                                         <span class="badge" style="background-color: #ffc107; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Partial Stocked</span>
                                                    @elseif ($requisition->status == 3 && $requisition->partial_stock == 1)
                                                        <span class="badge" style="background-color: #007bff; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Stocked</span> 

                                                    @elseif ($requisition->status == 5 && $requisition->partial_reject == 1)
                                                         <span class="badge" style="background-color: #dc3545; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Rejected</span>

                                                    @elseif ($requisition->status == 5 && $requisition->partial_reject == 0)
                                                         <span class="badge" style="background-color: #dc3545; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Partial Rejected</span>

                                                    @endif
                                                </td>

                                                <td>
                                                    <a href="{{ route('completed.order.list.view', $requisition->id) }}"
                                                        class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                        title="{{ __('View') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                        @if (!($requisition->status == 3 && $requisition->partial_stock == 1))
                                                            <a href="{{ route('completed.order.to.instock', $requisition->id) }}" 
                                                            class="btn btn-sm btn-success">In Stock
                                                            </a>
                                                        @endif  


                                                        <!-- @if ($requisition->status == 1)
                                                            <a href="{{ route('completed.order.to.instock', $requisition->id) }}" class="btn btn-sm btn-success">
                                                                In Stock
                                                            </a>
                                                        @endif -->




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
