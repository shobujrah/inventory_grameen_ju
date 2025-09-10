@extends('layouts.master')
@section('content')

    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Create Product</h3>
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
                    <div class="card comman-shadow">
                        <div class="card-body">
                                   

                            <div class="row">
                                <form action="{{ route('product.store')}}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3 col-md-12">
                                        <h5 class="form-title product-info" > Product Information </h5>
                                        <hr>
                                    </div> 


                                    <div class="mb-3 col-md-8">
                                        <label for="product_category_id" class="form-label">Category:<span style="color: red;">*</span></label>
                                        <select class="form-control select2" id="product_category_id" name="product_category_id" required>
                                            <option value="">-- Select Category --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="mb-3 col-md-8">
                                        <label for="name" class="form-label">Name:<span style="color: red; ">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>


                                    <div class="mb-3 col-md-8">
                                        <label for="product_type" class="form-label">Product Type:<span style="color: red;">*</span></label>
                                        <select class="form-control select2" id="product_type" name="product_type" required>
                                            <option value="">-- Select Product Type --</option>
                                            <option value="Regular">Regular</option>
                                            <option value="Standard">Standard</option>
                                        </select>
                                    </div>



                                    <div class="mb-3 col-md-8">
                                        <label for="description" class="form-label">Description:<span style="color: red; ">*</span></label>
                                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                                    </div>

                                    <div class="mb-3 col-md-8">
                                        <label for="price" class="form-label">Price:<span style="color: red; ">*</label>
                                        <input type="number" class="form-control" id="price" name="price" step="0.01" required min="0.01">
                                    </div>  


                                    <div class="mb-3 col-md-8">
                                        <label for="batch" class="form-label">Batch:</label>
                                        <input type="text" class="form-control" id="batch" name="batch" value="{{ $nextBatchNumber }}" readonly>
                                    </div>


                                    <div class="mb-3 col-md-8">
                                        <label for="sku" class="form-label">Barcode:</label>
                                        <input type="text" class="form-control" id="sku" name="sku">
                                    </div>

                                    <div class="mb-3 col-md-8">
                                        <label for="image" class="form-label">Image:<span style="color: red; ">(jpg, .jpeg, .png)</span></label>
                                        <input type="file" class="form-control" id="product_image" name="image" accept=".jpg, .jpeg, .png">
                                    </div>

                                    <a href="{{ route('product.create') }}" class="btn btn-info">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
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
