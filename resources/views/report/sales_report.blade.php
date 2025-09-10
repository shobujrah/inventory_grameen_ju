@extends('layouts.master')
@section('content')

<script type="text/javascript" src="{{ asset('assets/js/plugins/html2pdf.bundle.min.js') }}"></script>



<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Sales Report</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Sales Report</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- message --}}
        {!! Toastr::message() !!}

        @push('script-page')
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    dpi: 72,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A2'
                }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#filter").click(function() {
                $("#show_filter").toggle();
            });
        });
    </script>
        <script>
            $(document).ready(function () {
                callback();
                function callback() {
                    var start_date = $(".startDate").val();
                    var end_date = $(".endDate").val();

                    $('.start_date').val(start_date);
                    $('.end_date').val(end_date);

                }
                });

        </script>


@endpush


        <!-- <div class="row mb-1">
            <div class="col-md-12 text-end">

                <a href="" class="btn btn-sm btn-primary" onclick="saveAsPDF()" data-bs-toggle="tooltip" title="{{__('Download')}}" data-original-title="{{__('Download')}}">
                    <span class="btn-inner--icon"><i class="fas fa-download"></i></span>
                </a>

            </div>
        </div> -->


        <div class="float-end">
        
            <input type="hidden" name="start_date" class="start_date">
            <input type="hidden" name="end_date" class="end_date">
            <button onclick="printDiv('printarea')" type="submit" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('Print') }}" data-original-title="{{ __('Print') }}"><i class="fas fa-print"></i></button>
         
        </div>

        <div class="float-end me-2">
            {{ Form::open(['route' => ['sales.export']]) }}
            <input type="hidden" name="start_date" class="start_date">
            <input type="hidden" name="end_date" class="end_date">
            <button type="submit" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('Export') }}" data-original-title="{{ __('Export') }}"><i class="fas fa-download"></i></button>
            {{ Form::close() }}
        </div>

        <!-- <div class="float-end me-2" id="filter">
            <button id="filter" class="btn btn-sm btn-primary"><i class="ti ti-filter"></i></button>
        </div> -->

        <!-- <div class="float-end me-2">
            <a href="{{ route('report.balance.sheet' , 'horizontal')}}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('Horizontal View') }}" data-original-title="{{ __('Horizontal View') }}"><i class="ti ti-separator-vertical"></i></a>
        </div> -->

        
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="mt-2" id="multiCollapseExample1">
                        <div class="card" id="show_filter">
                            <div class="card-body">
                            {{ Form::open(['route' => ['report.sales'], 'method' => 'GET', 'id' => 'report_bill_summary']) }}
                                <div class="row align-items-center justify-content-end">
                                    <div class="col-xl-10">
                                        <div class="row">
                                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                                <div class="btn-box">
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                                <div class="btn-box">
                                                </div>
                                            </div>
                                            {{-- <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                            <div class="btn-box">
                                                {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('start_date', $filter['startDateRange'], ['class' => 'startDate form-control']) }}
                                        </div>
                                    </div> --}}
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('start_date', $filter['startDateRange'], ['class' => 'startDate form-control']) }}
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('end_date', $filter['endDateRange'], ['class' => 'endDate form-control']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto mt-4">
                                <div class="row">
                                    <div class="col-auto">
                                        <a href="#" class="btn btn-sm btn-primary" onclick="document.getElementById('report_bill_summary').submit(); return false;" data-bs-toggle="tooltip" title="{{ __('Apply') }}" data-original-title="{{ __('apply') }}">
                                        <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
                                        </a>

                                        <a href="{{ route('report.sales') }}" class="btn btn-sm btn-danger " data-bs-toggle="tooltip" title="{{ __('Reset') }}" data-original-title="{{ __('Reset') }}">
                                        <span class="btn-inner--icon"><i class="fas fa-trash-alt"></i></span>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
 

    

    {{-- <div class="row mb-4">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table table-flush">
                                <thead>
                                    <tr>
                                        <th width="50%"> {{ __('Account Name') }}</th>
    <th width="25%"> {{ __('Account Code') }}</th>
    <th width="25%"> {{ __('Total') }}</th>
    </tr>
    </thead>
    </table>
    @foreach ($chartAccounts as $type => $accounts)
    <p class="font-bold ms-3 mt-2 fs-4">{{ $type }}</p>
    <hr>
    @foreach ($accounts as $account)
    <p class="text-primary ms-3 mt-2 fs-5">{{ $account['subType'] }}</p>
    <table class="table">
        <tbody>
            @foreach ($account['account'] as $record)
            @php
            $totalCredit = 0;
            $totalDebit = 0;
            $totalBalance = 0;

            $totalCredit += $record['totalCredit'];
            $totalDebit += $record['totalDebit'];
            $getAccount = \App\Models\ChartOfAccount::where('name', $record['account_name'])->first();
            $Balance = App\Models\Utility::getAccountBalance($getAccount->id, $filter['startDateRange'], $filter['endDateRange']);
            $totalBalance += $Balance;
            @endphp
            <tr>
                <td width="50%">{{ $record['account_name'] }} </td>
                <td width="25%">{{ $record['account_code'] }} </td>
                <td width="25%">{{ $record['netAmount'] + $totalBalance  }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach
    @endforeach
</div>
</div>
</div>
</div>
</div> --}}


        @php
            $user = \Auth::user();
        @endphp

<div id="printarea">
 <div class="row">
        <div class="col-12" id="invoice-container">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between w-100">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="profile-tab3" data-bs-toggle="pill" href="#item" role="tab" aria-controls="pills-item" aria-selected="true">{{__('Sales by Item')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab4" data-bs-toggle="pill" href="#customer" role="tab" aria-controls="pills-customer" aria-selected="false">{{__('Sales by Customer')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="tab-content" id="myTabContent2">
                                <div class="tab-pane fade fade show active" id="item" role="tabpanel" aria-labelledby="profile-tab3">
                                    <div class="table-responsive">
                                    <table class="table table-flush datatable" id="report-dataTable">
                                        <thead>
                                        <tr>
                                            <th width="33%"> {{__('Invoice Item')}}</th>
                                            <th width="33%"> {{__('Quantity Sold')}}</th>
                                            <th width="33%"> {{__('Amount')}}</th>
                                            <th class="text-end"> {{__('Average Price')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoiceItems as $invoiceItem)
                                                <tr>
                                                    <td>{{ $invoiceItem['name']}}</td>
                                                    <td>{{ $invoiceItem['quantity']}}</td>
                                                    <td>{{ \App\Models\Utility::priceFormat($invoiceItem['price']) }}</td>
                                                    <td>{{ \App\Models\Utility::priceFormat($invoiceItem['avg_price']) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                </div>

                                <div class="tab-pane fade fade" id="customer" role="tabpanel" aria-labelledby="profile-tab3">
                                    <div class="table-responsive">
                                    <table class="table table-flush datatable" id="report-dataTable">
                                        <thead>
                                        <tr>
                                            <th width="33%"> {{__('Customer Name')}}</th>
                                            <th width="33%"> {{__('Invoice Count')}}</th>
                                            <th width="33%"> {{__('Sales')}}</th>
                                            <th class="text-end"> {{__('Sales With Tax')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoiceCustomers as $invoiceCustomer)
                                                <tr>
                                                    <td>{{ $invoiceCustomer['name'] }}</td>
                                                    <td>{{ $invoiceCustomer['invoice_count']}}</td>
                                                    <td>{{ \App\Models\Utility::priceFormat($invoiceCustomer['price']) }}</td>
                                                    <td>{{ \App\Models\Utility::priceFormat($invoiceCustomer['price'] + $invoiceCustomer['total_tax']) }}</td>
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
        </div>
    </div>
 </div>
 
<script>
    function printDiv(divId){
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>

@endsection