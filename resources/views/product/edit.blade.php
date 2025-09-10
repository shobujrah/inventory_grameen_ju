
@extends('layouts.master')

@section('content')

    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Product Edit</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active"> <a href="{{ route('product.list') }}">Product</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- message --}}
            {!! Toastr::message() !!}
            <div class="row">
                <div class="col-sm-12">
                    <div class="card comman-shadow">
                        <div class="card-body">

                            <div class="row">

                                <form action="{{ route('product.update', $productEdit->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3 col-md-12">
                                        <h5 class="form-title product-info" > Product Information </h5>
                                        <hr>
                                    </div> 



                                    {{-- Category Selection --}}
                                    <div class="mb-3 col-md-8">
                                        <label for="product_category_id" class="form-label">Category:<span style="color: red;">*</span></label>
                                        <select class="form-control select2" id="product_category_id" name="product_category_id" required>
                                            <option value="">-- Select Category --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('product_category_id', $productEdit->product_category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Batch (Read-Only) --}}
                                    <div class="mb-3 col-md-8">
                                        <label for="batch" class="form-label">Batch:</label>
                                        <input type="text" class="form-control" id="batch" name="batch" value="{{ old('batch', $productEdit->batch) }}" readonly>
                                    </div>

                                    
                                    <div class="mb-3 col-md-8">
                                        <label for="name">Name:<span style="color: red; ">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $productEdit->name) }}" required>
                                    </div>

                                    <div class="mb-3 col-md-8">
                                        <label for="product_type" class="form-label">Product Type:<span style="color: red;">*</span></label>
                                        <select class="form-control select2" id="product_type" name="product_type" required>
                                            <option value="">-- Select Product Type --</option>
                                            <option value="Regular" {{ old('product_type', $productEdit->product_type) == 'Regular' ? 'selected' : '' }}>Regular</option>
                                            <option value="Standard" {{ old('product_type', $productEdit->product_type) == 'Standard' ? 'selected' : '' }}>Standard</option>
                                        </select>
                                    </div>


                                    <div class="mb-3 col-md-8">
                                        <label for="description">Description:<span style="color: red; ">*</span></label>
                                        <textarea class="form-control" id="description" name="description" required>{{ old('description', $productEdit->description) }}</textarea>
                                    </div>

                                    <div class="mb-3 col-md-8">
                                        <label for="price">Price:<span style="color: red; ">*</span></label>
                                        <input type="number" step="0.01" min="0.01" class="form-control" id="price" name="price" value="{{ old('price', $productEdit->price) }}" required>
                                    </div>

                                    <div class="mb-3 col-md-8">
                                        <label for="sku">Barcode</label>
                                        <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku', $productEdit->sku) }}">
                                    </div>

                                    <div class="mb-3 col-md-8">
                                        <label for="image">Image</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                        <small class="form-text text-muted">"Leave blank to keep the current image"  </small>
                                        @if($productEdit->image)
                                            <img src="{{ asset('storage/products/' . $productEdit->image) }}" alt="{{ $productEdit->name }}" style="width: 60px; height: 60px; object-fit: cover; margin-top: 10px;">
                                        @endif
                                    </div>

                                    <a href="{{ route('product.list') }}" class="btn btn-info">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection  


@section('script')

<script>
    $('.select2').select2();
</script>

<style>
    .select2-container .select2-selection--single {
        height: 42px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 42px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px;
    }
</style>

@endsection
