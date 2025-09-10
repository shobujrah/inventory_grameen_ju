@extends('layouts.master')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Product List </h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('product.list') }}">Product</a></li>
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
                        <form action="{{ route('barcode.generate') }}" method="POST">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="product" class="form-label">Select Product</label>
                                    <select name="product" id="product" class="form-control select2" required>
                                        <option value="" disabled selected>--Select Product--</option>
                                        @foreach ($productList as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="1" required>
                                </div>

                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary" style="margin-top: 34px;">Generate Barcode</button>
                                </div>

                            </div>
                        </form>

                        </br>

                        <div class="table-responsive">
                            <table class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                <thead class="product-thread">
                                    <tr>
                                        <th>Serial</th>
                                        <th>Name</th>
                                        <th>Barcode</th>
                                        <th class="text-center">Barcode Reader</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productList as $product)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>
                                            <div class="text-center d-flex align-items-center flex-column">
                                                {!! $product->barcode !!}
                                            </div>
                                        </td>
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