@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Expense List</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active"><a>List</a></li>
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
                                            <th>Expense BY</th>
                                            <th>Branch Name</th>
                                            <th>Consignee Name</th>
                                            <th>Epense Date</th>
                                            <th>Product Name</th>
                                            <th>Expense Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productexpense as $key => $productexpenses)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td><span class="badge md bg-success">{{ $productexpenses->user->name }}</span></td>
                                                <td>{{ $productexpenses->branch->name ?? ''}}</td>
                                                <td>{{ $productexpenses->consignee_name }}</td>
                                                <!-- <td>{{ $productexpenses->expense_date }}</td> -->
                                                <td>{{ \Carbon\Carbon::parse($productexpenses->expense_date)->format('d/m/Y') }}</td>
                                                <td>{{ $productexpenses->product->name ?? '' }}</td>
                                                <td>{{ $productexpenses->expense_amount }}</td>
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
