
@extends('layouts.master')

@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Product Account Map List</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active"> <a href="{{ route('product_account_map.index') }}">List</a></li>
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
                                
                            </div>
                        </div>

                        </br>

                        <div class="table-responsive">
                            <table class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                <thead class="product-thread">
                                    <tr>
                                        <th>Serial</th>
                                        <th>Category</th>
                                        <th>Product Code </th>
                                        <th>Product Name</th>
                                        <th>Inventory Asset Code </th>
                                        <th>EXpense Code </th>
                                        <th>Income code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productaccmap as $productaccmaplist)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $productaccmaplist->category->name ?? '' }}</td>
                                        <td>{{ $productaccmaplist->product_code }}</td>
                                        <td>{{ $productaccmaplist->product_name }}</td>
                                        <td>{{ $productaccmaplist->account_asset_inventory_code }}</td>
                                        <td>{{ $productaccmaplist->account_expense_code }}</td>
                                        <td>{{ $productaccmaplist->account_income_code }}</td>
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