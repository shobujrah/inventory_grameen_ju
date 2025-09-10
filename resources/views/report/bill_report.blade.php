@extends('layouts.master')
@section('content')

<script type="text/javascript" src="{{ asset('assets/js/plugins/html2pdf.bundle.min.js') }}"></script>



<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Bill Summary</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Bill Summary</li>
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


        <div class="float-end me-2">
            {{ Form::open(['route' => ['balance.sheet.export']]) }}
            <input type="hidden" name="start_date" class="start_date">
            <input type="hidden" name="end_date" class="end_date">
            <button type="submit" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('Export') }}" data-original-title="{{ __('Export') }}"><i class="fas fa-download"></i></button>
            {{ Form::close() }}
        </div>

<br> </br>

    <div class="row">
        <div class="col-sm-12">
            <div class="mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('route' => array('report.bill.summary'),'method' => 'GET','id'=>'report_bill_summary')) }}
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('start_month', __('Start Month'),['class'=>'form-label']) }}

                                            {{Form::month('start_month',isset($_GET['start_month'])?$_GET['start_month']:date('Y-m', strtotime("-5 month")),array('class'=>'month-btn form-control'))}}

                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('end_month', __('End Month'),['class'=>'form-label']) }}
                                            {{Form::month('end_month',isset($_GET['end_month'])?$_GET['end_month']:date('Y-m'),array('class'=>'month-btn form-control'))}}

                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('vender', __('Vender'),['class'=>'form-label']) }}
                                            {{ Form::select('vender',$vender,isset($_GET['vender'])?$_GET['vender']:'', array('class' => 'form-control select')) }}

                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('status', __('Status'),['class'=>'form-label']) }}

                                            {{ Form::select('status', [''=>'Select Status']+$status,isset($_GET['status'])?$_GET['status']:'', array('class' => 'form-control select')) }}
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col-auto mt-4">

                                        <a href="#" class="btn btn-sm btn-primary" onclick="document.getElementById('report_bill_summary').submit(); return false;" data-bs-toggle="tooltip" title="{{__('Apply')}}" data-original-title="{{__('apply')}}">
                                            <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
                                        </a>

                                        <a href="{{route('report.bill.summary')}}" class="btn btn-sm btn-danger " data-bs-toggle="tooltip"  title="{{ __('Reset') }}" data-original-title="{{__('Reset')}}">
                                            <span class="btn-inner--icon"><i class="fas fa-trash-alt "></i></span>
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
    </div>
    

   
    <div id="printableArea">
        <div class="row mt-3">
            <div class="col">
                <input type="hidden" value="{{$filter['status'].' '.__('Bill').' '.'Report of'.' '.$filter['startDateRange'].' to '.$filter['endDateRange'].' '.__('of').' '.$filter['vender']}}" id="filename">
                <div class="card p-4 mb-4">
                    <h7 class="report-text gray-text mb-0">{{__('Report')}} :</h7>
                    <h6 class="report-text mb-0">{{__('Bill Summary')}}</h6>
                </div>
            </div>
            @if($filter['vender']!= __('All'))
                <div class="col">
                    <div class="card p-4 mb-4">
                        <h7 class="report-text gray-text mb-0">{{__('Vendor')}} :</h7>
                        <h6 class="report-text mb-0">{{$filter['vender']}}</h6>
                    </div>
                </div>
            @endif
            @if($filter['status']!= __('All'))
                <div class="col">
                    <div class="card p-4 mb-4">
                        <h7 class="report-text gray-text mb-0">{{__('Status')}} :</h7>
                        <h6 class="report-text mb-0">{{$filter['status']}}</h6>
                    </div>
                </div>
            @endif
            <div class="col">
                <div class="card p-4 mb-4">
                    <h7 class="report-text gray-text mb-0">{{__('Duration')}} :</h7>
                    <h6 class="report-text mb-0">{{$filter['startDateRange'].' to '.$filter['endDateRange']}}</h6>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card p-4 mb-4">
                    <h7 class="report-text gray-text mb-0">{{__('Total Bill')}}</h7>
                    <h6 class="report-text mb-0">{{ \App\Models\Utility::priceFormat($totalBill)}}</h6>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card p-4 mb-4">
                    <h7 class="report-text gray-text mb-0">{{__('Total Paid')}}</h7>
                    <h6 class="report-text mb-0">{{\App\Models\Utility::priceFormat($totalPaidBill)}}</h6>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card p-4 mb-4">
                    <h7 class="report-text gray-text mb-0">{{__('Total Due')}}</h7>
                    <h6 class="report-text mb-0">{{\App\Models\Utility::priceFormat($totalDueBill)}}</h6>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12" id="bill-container">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between w-100">


                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="profile-tab3" data-bs-toggle="pill" href="#summary" role="tab" aria-controls="pills-summary" aria-selected="true">{{__('Summary')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-tab4" data-bs-toggle="pill" href="#bills" role="tab" aria-controls="pills-invoice" aria-selected="false">{{__('Bills')}}</a>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="tab-content" id="myTabContent2">
                                    <div class="tab-pane fade fade" id="bills" role="tabpanel" aria-labelledby="profile-tab3">
                                        <div class="table-responsive">
                                        <table class="table table-flush datatable" id="report-dataTable">
                                            <thead>
                                            <tr>
                                                <th> {{__('Bill')}}</th>
                                                <th> {{__('Date')}}</th>
                                                <th> {{__('Customer')}}</th>
                                                <th> {{__('Category')}}</th>
                                                <th> {{__('Status')}}</th>
                                                <th> {{__('	Paid Amount')}}</th>
                                                <th> {{__('Due Amount')}}</th>
                                                <th> {{__('Payment Date')}}</th>
                                                <th> {{__('Amount')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($bills as $bill)
                                                <tr>
                                                    <td class="Id">
                                                        {{--                                                        <a href="{{ route('bill.show',$bill->id) }}">--}}
                                                        {{--                                                            {{ \App\Models\Utility::billNumberFormat($bill->bill_id) }}--}}
                                                        {{--                                                        </a>--}}
                                                        <a href="{{ route('bill.show',\Crypt::encrypt($bill->id)) }}" class="btn btn-outline-primary">{{ \App\Models\Utility::billNumberFormat($bill->bill_id) }}</a>
                                                    </td>

                                                    </td>
                                                    <td>{{ \App\Models\Utility::dateFormat($bill->send_date) }}</td>
                                                    <td> {{!empty($bill->vender)? $bill->vender->name:'-' }} </td>
                                                    <td>{{ !empty($bill->category)?$bill->category->name:'-'}}</td>
                                                    <td>
                                                        @if($bill->status == 0)
                                                            <span class="badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                                        @elseif($bill->status == 1)
                                                            <span class="badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                                        @elseif($bill->status == 2)
                                                            <span class="badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                                        @elseif($bill->status == 3)
                                                            <span class="badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                                        @elseif($bill->status == 4)
                                                            <span class="badge bg-success p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                                        @endif
                                                    </td>
                                                    <td> {{ \App\Models\Utility::priceFormat($bill->getTotal()-$bill->getDue())}}</td>
                                                    <td> {{ \App\Models\Utility::priceFormat($bill->getDue())}}</td>
                                                    <td>{{!empty($bill->lastPayments)?\App\Models\Utility::dateFormat($bill->lastPayments->date):''}}</td>
                                                    <td> {{ \App\Models\Utility::priceFormat($bill->getTotal())}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    </div>
                                    <div class="tab-pane fade fade show active" id="summary" role="tabpanel" aria-labelledby="profile-tab3">
                                        <div class="scrollbar-inner">
                                            <div id="chart-sales" data-color="primary" data-type="bar" data-height="300"></div>
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
@endsection
