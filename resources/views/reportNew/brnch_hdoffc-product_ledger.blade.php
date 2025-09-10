@extends('layouts.master')
@section('content')
<script type="text/javascript" src="{{ asset('assets/js/plugins/html2pdf.bundle.min.js') }}"></script>
<script>
    function saveAsPDF() {
        var element = document.getElementById('printableArea');
        var opt = {
            margin: 0.3,
            filename: 'Ledger-Report-{{date('d-m-Y')}}', 
            image: {type: 'jpeg', quality: 1},
            html2canvas: {scale: 4, dpi: 72, letterRendering: true},
            jsPDF: {unit: 'in', format: 'A4'}
        };
        html2pdf().set(opt).from(element).save();
    }

    $(document).ready(function () {
        $('#report-dataTable').DataTable({
            dom: 'lBfrtip',
            buttons: [
                { extend: 'excel', title: 'Ledger Report' },
                { extend: 'pdf', title: 'Ledger Report' },
                { extend: 'csv', title: 'Ledger Report' }
            ]
        });
    });
</script>

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Ledger Report</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active"><a>Ledger</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- message --}}
            {!! Toastr::message() !!}

            <div class="row mb-1">
                <div class="col-md-12 text-end">
                    
                    <!-- <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()"data-bs-toggle="tooltip" title="{{__('Download')}}" data-original-title="{{__('Download')}}">
                        <span class="btn-inner--icon"><i class="fas fa-download"></i></span>
                    </a> -->

                    <a href="{{ route('product.ledger', array_merge(request()->all(), ['pdf' => 'true'])) }}" 
                        class="btn btn-sm btn-primary" 
                        data-bs-toggle="tooltip" 
                        title="{{__('Download')}}">
                            <span class="btn-inner--icon"><i class="fas fa-download"></i></span>
                    </a>
                    
                </div>
            </div>
            

            <div class="row">
                <div class="col-sm-12">
                    <div class="" id="multiCollapseExample1">
                        <div class="card">
                            <div class="card-body"> 

                                <form method="GET" action="{{ route('product.ledger') }}">
                                    <div class="row justify-content-end">
                                        <div class="col-md-4">
                                            <label for="product_id">Product</label>
                                            <select name="product_id" class="form-control select2" required>
                                                <option value="">--Select Product--</option>
                                                <option value="all" {{ request('product_id') == 'all' ? 'selected' : '' }}>All Products</option>
                                                @foreach ($allProducts as $product)
                                                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="month_year">Month and Year</label>
                                            <input type="month" name="month_year" class="form-control" value="{{ request('month_year') }}" required>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="branch_id">Branch <span class="text-danger">*</span></label>
                                            <select name="branch_id" class="form-control" {{ !$isAdmin ? 'readonly disabled' : '' }} required>
                                                <option value="">-- Select Branch --</option>
                                                @foreach($branches as $id => $name)
                                                    <option value="{{ $id }}" {{ $selectedBranch == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @if(!$isAdmin)
                                                <input type="hidden" name="branch_id" value="{{ $selectedBranch }}">
                                            @endif
                                        </div>
                                        <div class="col-md-2" style="margin-top: 30px;">
                                            <button type="submit" class="btn btn-primary"><span class="btn-inner--icon"><i class="fas fa-search"></i></span></button>
                                            <a href="{{ route('product.ledger') }}" class="btn btn-danger">
                                                <span class="btn-inner--icon"><i class="fas fa-trash-alt"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div>
                </div>
            </div>


            @if(request('product_id') != '')
            
                @php
                    $name = (request('product_id') == 'all') ? 'All Products' : \App\Models\Product::productName(request('product_id'));
                @endphp

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table comman-shadow">
                            <div class="card-body" id="printableArea">
                                <div class="page-header">
                                    <div style="font-size:20px;width:100%;text-align: center"><b>Ledger Report</b></div>
                                    <div style="font-size:17px;width:100%;text-align: center">
                                        Branch Name: {{ $branches[$selectedBranch] ?? 'N/A' }}
                                    </div>
                                    <div style="font-size:17px;width:100%;text-align: center">
                                        Product Name: {{$name}}
                                    </div>

                                    <div style="font-size:17px;width:100%;text-align: center">
                                        @if(request('month_year'))
                                            {{ date('F/Y', strtotime(request('month_year'))) }}
                                        @endif
                                    </div>

                                </div>

                                <div class="table-responsive"> 
                                    @php
                                        // Initialize grand total variables
                                        $grandTotalUnitPrice = 0;
                                        $grandTotalQuantity = 0;
                                        $grandTotalTotalPrice = 0;
                                        $grandTotalPurchaseQuantity = 0;
                                        $grandTotalPurchasePrice = 0;
                                        $grandTotalSalesQuantity = 0;
                                        $grandTotalSalesPrice = 0;
                                        $grandTotalStockQuantity = 0;
                                        $grandTotalStockAmount = 0;
                                    @endphp

                                    <table class="table table-bordered table-center result-table">
                                        <thead class="warehouse-thread">
                                            <tr>
                                                <th>Sl</th>
                                                <th>Product</th>
                                                <th>Unit Price</th>
                                                <th>Quantity</th>
                                                <th>Total Price</th>
                                                <th class="text-center">This month <br>purchase quantity</th>
                                                <th class="text-center">This month <br> purchase price</th>
                                                <th class="text-center">This month<br>expense quantity</th>
                                                <th class="text-center">This month<br>expense price</th>
                                                <th class="text-center">Total Stock quantity<br>End of the month</th>
                                                <th class="text-center">Total Stock Amount<br>End of the month</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $sl = 1;
                                            @endphp
                                            @foreach($groupedProducts as $categoryName => $products)
                                                @if(request('product_id') == 'all' || count($groupedProducts) == 1)
                                                    <tr>
                                                        <td class="text-center" colspan="11" style="background-color: #f8f9fa; font-weight: bold;">{{ $categoryName }}</td>
                                                    </tr>
                                                @endif
                                                @php
                                                    $subtotalUnitPrice = 0;
                                                    $subtotalQuantity = 0;
                                                    $subtotalTotalPrice = 0;
                                                    $subtotalPurchaseQuantity = 0;
                                                    $subtotalPurchasePrice = 0;
                                                    $subtotalSalesQuantity = 0;
                                                    $subtotalSalesPrice = 0;
                                                    $subtotalStockQuantity = 0;
                                                    $subtotalStockAmount = 0;
                                                @endphp
                                                @foreach($products as $productnamepirce)
                                                    @php
                                                        $product = $productDetails[$productnamepirce->product_id] ?? null;
                                                        $ledgerKey = $productnamepirce->product_id . '-' . $productnamepirce->price;
                                                        $ledgerEntry = $ledgerData[$ledgerKey] ?? null;
                                                        $purchaseQuantity = $ledgerEntry ? $ledgerEntry->total_quantity : 0;

                                                        // Get final quantity for the product and price
                                                        $finalQuantity = $finalQuantities[$ledgerKey] ?? 0;

                                                        // Get sales quantity for the product and price
                                                        $salesEntry = $salesData[$ledgerKey] ?? null;
                                                        $salesQuantity = $salesEntry ? $salesEntry->total_quantity : 0;

                                                        // Calculate totals for each column
                                                        $subtotalUnitPrice += $productnamepirce->price;
                                                        $subtotalQuantity += $finalQuantity;
                                                        $subtotalTotalPrice += $finalQuantity * $productnamepirce->price;
                                                        $subtotalPurchaseQuantity += $purchaseQuantity;
                                                        $subtotalPurchasePrice += $purchaseQuantity * $productnamepirce->price;
                                                        $subtotalSalesQuantity += $salesQuantity;
                                                        $subtotalSalesPrice += $salesQuantity * $productnamepirce->price;
                                                        $subtotalStockQuantity += ($finalQuantity + $purchaseQuantity) - $salesQuantity;
                                                        $subtotalStockAmount += (($finalQuantity * $productnamepirce->price) + ($purchaseQuantity * $productnamepirce->price)) - ($salesQuantity * $productnamepirce->price);

                                                        // Accumulate grand totals
                                                        $grandTotalUnitPrice += $productnamepirce->price;
                                                        $grandTotalQuantity += $finalQuantity;
                                                        $grandTotalTotalPrice += $finalQuantity * $productnamepirce->price;
                                                        $grandTotalPurchaseQuantity += $purchaseQuantity;
                                                        $grandTotalPurchasePrice += $purchaseQuantity * $productnamepirce->price;
                                                        $grandTotalSalesQuantity += $salesQuantity;
                                                        $grandTotalSalesPrice += $salesQuantity * $productnamepirce->price;
                                                        $grandTotalStockQuantity += ($finalQuantity + $purchaseQuantity) - $salesQuantity;
                                                        $grandTotalStockAmount += (($finalQuantity * $productnamepirce->price) + ($purchaseQuantity * $productnamepirce->price)) - ($salesQuantity * $productnamepirce->price);
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $sl++ }}</td>
                                                        <td>{{ $productDetails[$productnamepirce->product_id]->name ?? 'N/A' }}</td>
                                                        <td class="text-end">{{ $productnamepirce->price ?? 'N/A' }}</td>
                                                        <td class="text-center">{{ $finalQuantity }}</td>
                                                        <td class="text-end">{{ number_format($finalQuantity * $productnamepirce->price, 2) }}</td>
                                                        <td class="text-center">{{ $purchaseQuantity }}</td>
                                                        <td class="text-end">{{ number_format($purchaseQuantity * $productnamepirce->price, 2) }}</td>
                                                        <td class="text-center">{{ $salesQuantity }}</td>
                                                        <td class="text-end">{{ number_format($salesQuantity * $productnamepirce->price, 2) }}</td>
                                                        <td class="text-center">{{ ($finalQuantity + $purchaseQuantity) - $salesQuantity }}</td>
                                                        <td class="text-end">
                                                            {{ number_format((($finalQuantity * $productnamepirce->price) + ($purchaseQuantity * $productnamepirce->price)) - ($salesQuantity * $productnamepirce->price), 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="2" class="text-end"><strong>Subtotal</strong></td>
                                                    <td class="text-end"><strong>{{ number_format($subtotalUnitPrice, 2) }}</strong></td>
                                                    <td class="text-center"><strong>{{ $subtotalQuantity }}</strong></td>
                                                    <td class="text-end"><strong>{{ number_format($subtotalTotalPrice, 2) }}</strong></td>
                                                    <td class="text-center"><strong>{{ $subtotalPurchaseQuantity }}</strong></td>
                                                    <td class="text-end"><strong>{{ number_format($subtotalPurchasePrice, 2) }}</strong></td>
                                                    <td class="text-center"><strong>{{ $subtotalSalesQuantity }}</strong></td>
                                                    <td class="text-end"><strong>{{ number_format($subtotalSalesPrice, 2) }}</strong></td>
                                                    <td class="text-center"><strong>{{ $subtotalStockQuantity }}</strong></td>
                                                    <td class="text-end"><strong>{{ number_format($subtotalStockAmount, 2) }}</strong></td>
                                                </tr>
                                                @php
                                                    $roundSubtotalUnitPrice = round($subtotalUnitPrice);
                                                    $roundSubtotalTotalPrice = round($subtotalTotalPrice);
                                                    $roundSubtotalPurchasePrice = round($subtotalPurchasePrice);
                                                    $roundSubtotalSalesPrice = round($subtotalSalesPrice);
                                                    $roundSubtotalStockAmount = round($subtotalStockAmount);
                                                @endphp
                                                <tr>
                                                    <td colspan="2" class="text-end"><strong>Round of Subtotal</strong></td>
                                                    <td class="text-end"><strong>{{ number_format($roundSubtotalUnitPrice, 2) }}</strong></td>
                                                    <td class="text-center"><strong>{{ $subtotalQuantity }}</strong></td>
                                                    <td class="text-end"><strong>{{ number_format($roundSubtotalTotalPrice, 2) }}</strong></td>
                                                    <td class="text-center"><strong>{{ $subtotalPurchaseQuantity }}</strong></td>
                                                    <td class="text-end"><strong>{{ number_format($roundSubtotalPurchasePrice, 2) }}</strong></td>
                                                    <td class="text-center"><strong>{{ $subtotalSalesQuantity }}</strong></td>
                                                    <td class="text-end"><strong>{{ number_format($roundSubtotalSalesPrice, 2) }}</strong></td>
                                                    <td class="text-center"><strong>{{ $subtotalStockQuantity }}</strong></td>
                                                    <td class="text-end"><strong>{{ number_format($roundSubtotalStockAmount, 2) }}</strong></td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="2" class="text-end"><strong>Total</strong></td>
                                                <td class="text-end"><strong>{{ number_format($grandTotalUnitPrice, 2) }}</strong></td>
                                                <td class="text-center"><strong>{{ $grandTotalQuantity }}</strong></td>
                                                <td class="text-end"><strong>{{ number_format($grandTotalTotalPrice, 2) }}</strong></td>
                                                <td class="text-center"><strong>{{ $grandTotalPurchaseQuantity }}</strong></td>
                                                <td class="text-end"><strong>{{ number_format($grandTotalPurchasePrice, 2) }}</strong></td>
                                                <td class="text-center"><strong>{{ $grandTotalSalesQuantity }}</strong></td>
                                                <td class="text-end"><strong>{{ number_format($grandTotalSalesPrice, 2) }}</strong></td>
                                                <td class="text-center"><strong>{{ $grandTotalStockQuantity }}</strong></td>
                                                <td class="text-end"><strong>{{ number_format($grandTotalStockAmount, 2) }}</strong></td>
                                            </tr>
                                            @php
                                                $roundGrandTotalUnitPrice = round($grandTotalUnitPrice);
                                                $roundGrandTotalTotalPrice = round($grandTotalTotalPrice);
                                                $roundGrandTotalPurchasePrice = round($grandTotalPurchasePrice);
                                                $roundGrandTotalSalesPrice = round($grandTotalSalesPrice);
                                                $roundGrandTotalStockAmount = round($grandTotalStockAmount);
                                            @endphp
                                            <tr>
                                                <td colspan="2" class="text-end"><strong>Round of Total</strong></td>
                                                <td class="text-end"><strong>{{ number_format($roundGrandTotalUnitPrice, 2) }}</strong></td>
                                                <td class="text-center"><strong>{{ $grandTotalQuantity }}</strong></td>
                                                <td class="text-end"><strong>{{ number_format($roundGrandTotalTotalPrice, 2) }}</strong></td>
                                                <td class="text-center"><strong>{{ $grandTotalPurchaseQuantity }}</strong></td>
                                                <td class="text-end"><strong>{{ number_format($roundGrandTotalPurchasePrice, 2) }}</strong></td>
                                                <td class="text-center"><strong>{{ $grandTotalSalesQuantity }}</strong></td>
                                                <td class="text-end"><strong>{{ number_format($roundGrandTotalSalesPrice, 2) }}</strong></td>
                                                <td class="text-center"><strong>{{ $grandTotalStockQuantity }}</strong></td>
                                                <td class="text-end"><strong>{{ number_format($roundGrandTotalStockAmount, 2) }}</strong></td>
                                            </tr>
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
    <style>
        .card-table .table td, .card-table .table th{
            padding: 4px 5px;
        }
    </style> 

    <style>
        .result-table {
            border: 1px solid black !important;
        }
        .result-table th,
        .result-table td {
            border: 1px solid black !important;
        }
    </style>


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