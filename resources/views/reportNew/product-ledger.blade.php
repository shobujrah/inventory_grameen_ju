@extends('layouts.master')
@section('content')

<!-- <script type="text/javascript" src="{{ asset('assets/js/plugins/html2pdf.bundle.min.js') }}"></script>
<script>

    function saveAsPDF() {
        var filename = $('#filename').val();
        var element = document.getElementById('printableArea');
        var opt = {
            margin: 0.3,
            filename: filename,
            image: {type: 'jpeg', quality: 1},
            html2canvas: {scale: 4, dpi: 72, letterRendering: true},
            jsPDF: {unit: 'in', format: 'A4'}
        };
        html2pdf().set(opt).from(element).save();
    }

    $(document).ready(function () {
        var filename = $('#filename').val();
        $('#report-dataTable').DataTable({
            dom: 'lBfrtip',
            buttons: [
                {
                    extend: 'excel',
                    title: filename
                },
                {
                    extend: 'pdf',
                    title: filename
                },  {
                    extend: 'csv',
                    title: filename
                }
            ]
        });
    });
</script> -->


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
                            <h3 class="page-title">Store Report</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active"><a>Store</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- message --}}
            {!! Toastr::message() !!}

            <div class="row mb-1">
                <div class="col-md-12 text-end">
                    
                    <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()"data-bs-toggle="tooltip" title="{{__('Download')}}" data-original-title="{{__('Download')}}">
                        <span class="btn-inner--icon"><i class="fas fa-download"></i></span>
                    </a>
            
                </div>
            </div>
            

            <div class="row">
                <div class="col-sm-12">
                    <div class="" id="multiCollapseExample1">
                        <div class="card">
                            <div class="card-body">
                                
                                <form method="GET" action="{{ route('report.product.ledger') }}">
                                    <div class="row justify-content-end">
                                        <div class="col-md-4">
                                            <label for="product_id">Product Name</label>
                                            <select name="product_id" class="form-control select2" required>
                                                <option value="">--Select Product--</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="start_date">Start Date</label>
                                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="end_date">End Date</label>
                                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="branch_id">Branch Name</label>
                                            <select name="branch_id" class="form-control select2">
                                                <option value="">--Select Branch--</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                                        {{ $branch->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2" style="margin-top: 30px;">
                                            <button type="submit" class="btn btn-primary"><span class="btn-inner--icon"><i class="fas fa-search"></i></span></button>
                                            <a href="{{ route('report.product.ledger') }}" class="btn btn-danger">
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
            @if(request('product_id')!='')
            @php
                $name = \App\Models\Product::productName(request('product_id'));
            @endphp
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table comman-shadow">
                            <div class="card-body" id="printableArea">
                                <div class="page-header">
                                    <div style="font-size:20px;width:100%;text-align: center"><b>Store Report</b></div>
                                    <div style="font-size:17px;width:100%;text-align: center">
                                        Product Name: {{$name}}
                                    </div>
                                    <div style="font-size:17px;width:100%;text-align: center">
                                        From: {{ (request('start_date'))? date('d/m/Y', strtotime(request('start_date'))):'' }} &nbsp;&nbsp;
                                        To: {{ (request('end_date'))? date('d/m/Y', strtotime(request('end_date'))):'' }}
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-center mb-0 table-striped result-table">
                                        <thead class="warehouse-thread">
                                            <tr>
                                                <th>Sl</th>
                                                <th>Entry Date</th>
                                                <th>Entry By</th>
                                                <th>Branch Name</th>
                                                <th>Consignee Name</th>
                                                <th>Type</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($productledger)>0)
                                            <tr>
                                                <td colspan="6" style="text-align: right">Opening balance</td>
                                                <td class="text-center">{{ $openingBalance }}</td>
                                            </tr>
                                            @foreach ($productledger as $key => $productledgers)

                                                @if($productledgers->type=='Stock In')
                                                    @php
                                                        $openingBalance = $openingBalance + $productledgers->quantity;
                                                    @endphp
                                                @elseif ($productledgers->type=='Expense')
                                                    @php
                                                        $openingBalance = $openingBalance - $productledgers->quantity;
                                                    @endphp
                                                @else
                                                @endif
                                            
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($productledgers->entry_date)->format('d/m/Y') }}</td>
                                                    <td>{{ $productledgers->user->name ?? '' }}</td>
                                                    <td>{{ $productledgers->branch->name ?? ''}}</td>
                                                    <td>{{ $productledgers->consignee_name }}</td>
                                                    <td>{{ $productledgers->type }}</td>
                                                    <td class="text-center">{{ $productledgers->quantity }}</td>
                                                    </td> 
                                                </tr>
                                            @endforeach
                                            @endif
                                            <tr>
                                                <td colspan="6" style="text-align: right"><b>Closing balance</b></td>
                                                <td class="text-center"><b>{{$openingBalance}}</b></td>
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