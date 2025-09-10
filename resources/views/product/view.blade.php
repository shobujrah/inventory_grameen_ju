
@extends('layouts.master')

@section('content')

    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Product View</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active"> <a href="{{ route('product.list') }}">Product</a></li>
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
                                        <h5 class="form-title product-info" > Product Information </h5>
                                        <hr>
                                    </div>
                                    
                                    <div class="mb-2 col-md-8">
                                        <label for="name">Category: {{ $productView->productCategory->name ?? '' }}</label>
                                        <p></p>
                                    </div>
                                    <div class="mb-2 col-md-8">
                                        <label for="name">Name: {{ $productView->name }}</label>
                                        <p></p>
                                    </div>
                                    <div class="mb-2 col-md-8">
                                        <label for="name">Type: {{ $productView->product_type ?? '' }}</label>
                                        <p></p>
                                    </div>

                                    <div class="mb-2 col-md-8">
                                        <label for="description">Description: {{ $productView->description }}</label>
                                        <p></p>
                                    </div>

                                    <div class="mb-2 col-md-8">
                                        <label for="price">Price: {{ $productView->price }} BDT</label>
                                        <p></p>
                                    </div>
                                    <div class="mb-2 col-md-8">
                                        <label for="price">Batch: {{ $productView->batch }}</label>
                                        <p></p>
                                    </div>
                                    
                                    <div class="mb-2 col-md-8">
                                        <label for="image">
                                            Image: 
                                            @if(!empty($productView->image) && file_exists(public_path('storage/products/' . $productView->image)))
                                                <img src="{{ asset('storage/products/' . $productView->image) }}" alt="Product Image" style="width: 60px; height: 60px;">
                                            @else
                                                <span>No Image</span>
                                            @endif
                                        </label>
                                        <p></p>
                                    </div>

                                    <div class="mb-3 col-md-8">
                                        <a href="{{ route('product.list') }}" class="btn btn-info">Back</a>
                                    </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
