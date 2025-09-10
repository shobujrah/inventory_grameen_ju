@extends('layouts.master')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Product List</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active"> <a href="{{ route('product.list') }}">Product</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {!! Toastr::message() !!}

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body row">
                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">Product Information</h3>
                                </div>

                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="{{ route('product.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                        </div>

                        </br>

                        <div class="table-responsive">
                            <table class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                <thead class="product-thread">
                                    <tr>
                                        <th>Serial</th>
                                        <th>Category</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Batch</th>
                                        <th>Image</th>
                                        <th>Barcode</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productList as $product )
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $product->productCategory->name ?? '' }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->product_type ?? ''}}</td>
                                        <td>{{ $product->description }}</td>
                                        <td>{{ $product->price }}</td>
                                        <td>{{ $product->batch }}</td>
                                        <td>
                                            @if($product->image)
                                            <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                No Image
                                            @endif
                                        </td>

                                        <td>
                                            {{ $product->sku }}
                                        </td>


                                            <!-- <td>
                                                <div class="text-center d-flex align-items-center flex-column">
                                                    {!! $product->barcode !!}
                                                    <div style="background-color: #FFFFFF; color: #000000; font-size: 10px;">
                                                        {{ $product->sku }}</div>
                                                </div>
                                            </td> -->


                                        <td>
                                            <a href="{{ route('product.view', $product->id) }}"
                                                class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                title="{{ __('View') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('product.edit', $product->id) }}"
                                                class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                title="{{ __('Edit') }}">
                                                <i class="feather-edit"></i>
                                            </a>
                                            <a href="{{ route('product.delete', $product->id) }}"
                                                class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                title="{{ __('Delete') }}">
                                                <i class="fas fa-trash text-danger"></i>
                                            </a>
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