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
            filename: 'Ledger Report', 
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
                { extend: 'excel', title: 'Stock In Out Report' },
                { extend: 'pdf', title: 'Stock In Out Report' },
                { extend: 'csv', title: 'Stock In Out Report' }
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
                            <h3 class="page-title">Stock In Out Report</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active"><a>Stock In Out</a></li>
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

                                <form method="GET" action="{{ route('report.stock.in.out') }}">
                                    <div class="row justify-content-end">
                                        <div class="col-md-2">
                                            <label for="product_id">Product Name</label>
                                            <select name="product_id" class="form-control select2">
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
                                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="end_date">End Date</label>
                                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
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
                                        <div class="col-md-2">
                                            <label for="type">Type</label>
                                            <select name="type" class="form-control select2">
                                                <option value="">--Select Type--</option>
                                                <option value="Stock In" {{ request('type') == 'Stock In' ? 'selected' : '' }}>Stock In</option>
                                                <option value="Expense" {{ request('type') == 'Expense' ? 'selected' : '' }}>Expense</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2" style="margin-top: 30px;">
                                            <button type="submit" class="btn btn-primary"><span class="btn-inner--icon"><i class="fas fa-search"></i></span></button>
                                            <a href="{{ route('report.stock.in.out') }}" class="btn btn-danger">
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


 
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table comman-shadow">
                            <div class="card-body">
                                <div class="page-header">
                                    <div class="row align-items-center">
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table
                                        class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped"  id="printableArea">
                                        <thead class="warehouse-thread">
                                            <tr>
                                                <th>Sl</th>
                                                <th>Entry By</th>
                                                <th>Type</th>
                                                <th>Branch Name</th>
                                                @if(request('type')!='Stock In')
                                                <th>Consignee Name</th>
                                                @endif
                                                <th>Entry Date</th>
                                                <th>Product Name</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($productledger as $key => $productledgers)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td><span class="badge md bg-success">{{ $productledgers->user->name ?? '' }}</span></td>
                                                    <td>{{ $productledgers->type }}</td>
                                                    <td>{{ $productledgers->branch->name ?? ''}}</td>
                                                    @if(request('type')!='Stock In')
                                                    <td>{{ $productledgers->consignee_name }}</td>
                                                    @endif
                                                    <td>{{ \Carbon\Carbon::parse($productledgers->entry_date)->format('d/m/Y') }}</td>
                                                    <td>{{ $productledgers->product->name ?? '' }}</td>
                                                    <td class="text-center">{{ $productledgers->quantity }}</td>
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