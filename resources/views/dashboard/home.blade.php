
@extends('layouts.master')
@section('content')
{{-- message --}}
{!! Toastr::message() !!}
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>



            @php
                $user = auth()->user();
            @endphp

            @if ($user->role_name === 'Admin' || $user->role_name === 'Warehouse')
                @if ($alertproducts > 0)
                    <div class="alert d-flex justify-content-between align-items-center" role="alert" style="background-color: #ffcc00; color: #000; font-weight: bold;">
                        {{$alertproducts}} product has been requested for return. Please take the necessary actions!
                        <a href="{{ route('return.product.page') }}" class="text-decoration-underline">Click here</a>
                    </div>
                @endif
            @endif




            @php
                $user = auth()->user();
            @endphp

            @if ($user->role_name === 'Branch')

            <div class="row">
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Requisitions</h6>
                                <h3>{{ $requisitions }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/requisitions.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Completed Order</h6>
                                <h3>{{ $completedorders }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/completedorder.jpg') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Stock</h6>
                                <h3>{{ $stocks }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/stock.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Expense</h6>
                                <h3>{{ $expenses }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/expense.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Damages/Return</h6>
                                <h3>{{ $damages }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/damageandreturn.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Products</h6>
                                <h3> {{ $products}} </h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/products.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @endif







            @php
                $user = auth()->user();
            @endphp

            @if ($user->role_name === 'Warehouse')

            <div class="row">
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Requisitions</h6>
                                <h3>{{ $requisitions }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/requisitions.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Order Request</h6>
                                <h3>{{ $orderrequest }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/orderrequest.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Purchase List</h6>
                                <h3>{{ $puchaselists }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/purchaselist.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Reject List</h6>
                                <h3>{{ $rejectlists }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/rejectlist.jpg') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Stock</h6>
                                <h3>{{ $stocks }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/stock.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Expense</h6>
                                <h3>{{ $expenses }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/expense.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Damages/Return</h6>
                                <h3>{{ $damagelist }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/damageandreturn.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Products</h6>
                                <h3> {{ $products}} </h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/products.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @endif





            @php
                $user = auth()->user();
            @endphp

            @if ($user->role_name === 'PurchaseTeam')

            <div class="row">
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Pending Purchase</h6>
                                <h3>{{ $pendingpurchases }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/pendingpurchase.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Purchase Collection</h6>
                                <h3>{{ $ptpurchasecollections }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/purchaselist.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Reject Collection</h6>
                                <h3>{{ $ptpurchasereject }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/rejectlist.jpg') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Approved of Purchase</h6>
                                <h3>{{ $ptpurchaseapprovebyho }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/purchaseapprove.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Reject of Purchase</h6>
                                <h3>{{ $ptpurchaserejectho }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/rejectpurchase.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Products</h6>
                                <h3> {{ $products}} </h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/products.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-12 d-flex">
                <div class="card flex-fill student-space comman-shadow">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="card-title">Latest Product</h5>
                        <ul class="chart-list-out student-ellips">
                            <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table star-student table-hover table-center table-borderless table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Image</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productLists as $product)
                                        <tr>
                                            <td class="text-nowrap">{{ $loop->iteration }}</td>
                                            <td class="text-nowrap">{{ $product->name }}</td>
                                            <td class="text-nowrap">{{ $product->price }}</td>
                                            <td class="text-nowrap">
                                                @if(!empty($product->image) && file_exists(public_path('storage/products/' . $product->image)))
                                                    <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <span>No Image</span>
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
            @endif






            @php
                $user = auth()->user();
            @endphp

            @if ($user->role_name === 'Headoffice')

            <div class="row">
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Requisitions</h6>
                                <h3>{{ $requisitionho }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/requisitions.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Completed Order</h6>
                                <h3>{{ $completedorderho }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/completedorder.jpg') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Pending Approval</h6>
                                <h3>{{ $pendingapprovalforho }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/pendingapprovalho.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Approved Approval</h6>
                                <h3>{{ $approvedapprovalforho }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/purchaseapprove.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Reject Approval</h6>
                                <h3> {{ $rejectapprovalforho }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/rejectpurchase.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Stock</h6>
                                <h3>{{ $stocks }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/stock.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Expense</h6>
                                <h3>{{ $expenses }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/expense.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Damages/Return</h6>
                                <h3>{{ $forhodamages }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/damageandreturn.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Products</h6>
                                <h3> {{ $products}} </h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/products.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Branch</h6>
                                <h3>{{ $branchs }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/mainbranchpic.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Project</h6>
                                <h3> {{ $projects }} </h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/branchdashboard.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @endif








            @php
                $user = auth()->user();
            @endphp

            @if ($user->role_name === 'Admin')

            <div class="row">
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Requisitions</h6>
                                <h3>{{ $requisitions }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/requisitions.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Completed Order</h6>
                                <h3>{{ $completedorders }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/completedorder.jpg') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Pending Approval</h6>
                                <h3>{{ $pendingapprovalforho }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/pendingapprovalho.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Approved Approval</h6>
                                <h3>{{ $approvedapprovalforho }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/purchaseapprove.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Reject Approval</h6>
                                <h3> {{ $rejectapprovalforho }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/rejectpurchase.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Order Request</h6>
                                <h3>{{ $orderrequest }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/orderrequest.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Purchase List</h6>
                                <h3>{{ $puchaselistsadmincheck }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/purchaselist.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Reject List</h6>
                                <h3>{{ $rejectlistsadmincheck }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/rejectlist.jpg') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Stock</h6>
                                <h3>{{ $stocks }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/stock.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Expense</h6>
                                <h3>{{ $expenses }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/expense.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Damages/Return</h6>
                                <h3>{{ $damages }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/damageandreturn.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Products</h6>
                                <h3> {{ $products}} </h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/products.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Branch</h6>
                                <h3>{{ $branchs }}</h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/mainbranchpic.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-sm-6 col-12 d-flex">
                <div class="card bg-comman w-100">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-info">
                                <h6>Project</h6>
                                <h3> {{ $projects }} </h3>
                            </div>
                            <div class="db-icon">
                                <img src="{{ URL::to('assets/img/branchdashboard.png') }}" style="height: 50px; width: 50px;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @endif




            
        </div>




        @php
            $user = auth()->user();
        @endphp

        @if ($user->role_name !== 'PurchaseTeam')
            <div class="row">
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill student-space comman-shadow">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title">Latest Requisition</h5>
                            <ul class="chart-list-out student-ellips">
                                <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table star-student table-hover table-center table-borderless table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                                <th>Serial</th>
                                                <th>Branch Name</th>
                                                <th>Project Name</th>
                                                <th>Date</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($latestRequisitionLists as $latestrequisition)
                                        <tr>
                                            <td class="text-nowrap">{{ $loop->iteration }}</td>
                                            <td class="text-nowrap"> {{ $latestrequisition->branch->name ?? '' }}</td>
                                            <td class="text-nowrap"> {{ $latestrequisition->project->name ?? '' }}</td>
                                            <td class="text-nowrap"> {{ $latestrequisition->date_from }}</td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill student-space comman-shadow">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title">Latest Product</h5>
                            <ul class="chart-list-out student-ellips">
                                <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table star-student table-hover table-center table-borderless table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th>Image</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($branchproductsstock as $branchproductsstocks)
                                        <tr>
                                            <td class="text-nowrap">{{ $loop->iteration }}</td>
                                            <td class="text-nowrap">{{ $branchproductsstocks->name }}</td>
                                            <td class="text-nowrap">{{ $branchproductsstocks->price }}</td>

                                            <td class="text-nowrap">
                                                @if($branchproductsstocks->image && file_exists(public_path('storage/products/' . $branchproductsstocks->image)))
                                                    <img src="{{ asset('storage/products/' . $branchproductsstocks->image) }}" alt="{{ $branchproductsstocks->name }}" style="width: 20px; height: 20px; object-fit: cover;">
                                                @else
                                                    No Image
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

        @endif




    </div>
</div>


@endsection
