
@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Requisition</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Requisition</li>
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
                                        <!-- <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-download"></i> Download</a> -->
                                        <a href="{{ route('requisition.createreq',0) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Create">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                    <div >
                                    <h3 style="text-align: center; margin-top: 50px; text-decoration: underline; font-weight: bold;" class="page-title">Requisition Details</h3>
                                    </div>
                                </div>
                            </div>
                    
                        <!-- @if($requisition = App\Models\Requisition::first())
                        <div class="row mb-3">
                            <div style="font-weight: bold;">Branch Name: {{ $requisition->branch_name }} Project Name: {{ $requisition->project_name }}   Date: {{ $requisition->date_from }}</div> 
                        </div>
                        @endif -->

                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                    <thead class="warehouse-thread">
                                        <tr>
                                            <th>Serial</th>
                                            <th>Product Description</th>
                                            <th>Single Product Name</th>
                                            <th>Price</th>
                                            <th>Demand Amount</th>
                                            <th>Total Price</th>
                                            <th>Stock Level</th>
                                            <th>Purchase Authorization Amount</th>
                                            <th>Comment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requisitionss as $requisition)
                                        <tr>
                                            <td>{{$requisition->id}}</td>
                                            <td>{{$requisition->product_description}}</td>
                                            <td>{{$requisition->single_product_name}}</td>
                                            <td>{{$requisition->price}}</td>
                                            <td>{{$requisition->demand_amount}}</td>
                                            <td>{{$requisition->total_price}}</td>
                                            <td>{{$requisition->stock_level}}</td>
                                            <td>{{$requisition->purchase_authorization_amount}}</td>
                                            <td>{{$requisition->comment}}</td>
                                            <td>  
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

