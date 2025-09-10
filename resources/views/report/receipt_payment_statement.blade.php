
@extends('layouts.master')
@section('page-title')
    {{ __('Receipt & Payment Statement Report') }}
@endsection
@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive pt-4">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h5 class="pb-3">
                                        <span class="text-dark">{{ __('Receipt & Payment Statement Report') }}</span>
                                    </h5>
                                </div>
                                <div class="col-sm-12">
                                    {{ Form::open(['route' => ['report.receipt.payment.statement'], 'method' => 'get', 'id' => 'report_receipt_payment_statement']) }}
                                    <div class="row justify-content-end">
                                        <div class="col-xl-10">
                                            <div class="row">
                                                <div class="col-md-3">
                                                </div>
                                                <div class="col-md-3">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="month_year">Month and Year</label>
                                                    <input type="month" name="month_year" class="form-control" value="{{ request('month_year') }}" required>
                                                </div>
                                                <div class="col-md-2" style="margin-top: 30px;">
                                                    <a href="#" class="btn btn-sm btn-primary"
                                                        onclick="document.getElementById('report_receipt_payment_statement').submit(); return false;">
                                                        <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
                                                    </a>
                                                    <a href="{{ route('report.receipt.payment.statement') }}"
                                                        class="btn btn-sm btn-danger">
                                                        <span class="btn-inner--icon"><i
                                                                class="fas fa-trash-restore-alt"></i></span>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-primary" onclick="printDiv('printableArea')">
                                                        <span class="btn-inner--icon"><i class="fas fa-print"></i></span>
                                                    </a>
                                                </div>
                                            
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>

                        <div id="printableArea">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="tab-content" id="myTabContent">
                                                        <div class="tab-pane fade show active" id="account-statement" role="tabpanel"
                                                            aria-labelledby="home-tab">


                                                            <!-- 1st code -->

                                                            <!-- <table class="table table-flush" id="report-table">
                                                                <tbody>
                                                                    <tr>
                                                                        <th colspan="8" class="text-center">
                                                                            <h4>{{ __('Store Report') }}</h4>
                                                                            <h5>{{ __('Receipt & Payment Statement') }}</h5>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th width="15%">{{ __('Receipt') }}</th>
                                                                        <th width="15%">{{ __('This Month') }}<br>{{ \Carbon\Carbon::parse($filter['startDateRange'])->format('m-d-Y') }}<br>{{ \Carbon\Carbon::parse($filter['endDateRange'])->format('m-d-Y') }}</th>
                                                                        <th width="15%">{{ __('Current Year') }}<br>{{ \Carbon\Carbon::parse($filter['fiscalYearStart'])->format('m-d-Y') }}<br>{{ \Carbon\Carbon::parse($filter['fiscalYearEnd'])->format('m-d-Y') }}</th>
                                                                        <th width="15%">{{ __('Cumulative') }}</th>
                                                                        <th width="15%">{{ __('Payment') }}</th>
                                                                        <th width="15%">{{ __('This Month') }}<br>{{ \Carbon\Carbon::parse($filter['startDateRange'])->format('m-d-Y') }}<br>{{ \Carbon\Carbon::parse($filter['endDateRange'])->format('m-d-Y') }}</th>
                                                                        <th width="15%">{{ __('Current Year') }}<br>{{ \Carbon\Carbon::parse($filter['fiscalYearStart'])->format('m-d-Y') }}<br>{{ \Carbon\Carbon::parse($filter['fiscalYearEnd'])->format('m-d-Y') }}</th>
                                                                        <th width="15%">{{ __('Cumulative') }}</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>{{ __('Opening Balance') }}</th>
                                                                        <td class="text-right">{{ number_format($openingBalance, 2) }}</td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                    </tr>
                                                                    @foreach($receipts as $item)
                                                                        @if(!in_array($item['name'], $productReceiptNames))
                                                                        <tr>
                                                                            <th>{{ $item['name'] }}</th>
                                                                            <td class="text-right">{{ number_format($item['this_month'], 2) }}</td>
                                                                            <td class="text-right">{{ number_format($item['current_year'], 2) }}</td>
                                                                            <td class="text-right">{{ number_format($item['cumulative'], 2) }}</td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                        </tr>
                                                                        @endif
                                                                    @endforeach
                                                                    <tr>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>{{ __('Goods Sales:') }}</th>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                    </tr>
                                                                    @foreach($receipts as $item)
                                                                        @if(in_array($item['name'], $productReceiptNames))
                                                                        <tr>
                                                                            <th>{{ $item['name'] }}</th>
                                                                            <td class="text-right">{{ number_format($item['this_month'], 2) }}</td>
                                                                            <td class="text-right">{{ number_format($item['current_year'], 2) }}</td>
                                                                            <td class="text-right">{{ number_format($item['cumulative'], 2) }}</td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                        </tr>
                                                                        @endif
                                                                    @endforeach

                                                                    @foreach($payments as $item)
                                                                        @if(!in_array($item['name'], $productPaymentNames))
                                                                        <tr>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <th>{{ $item['name'] }}</th>
                                                                            <td class="text-right">{{ number_format($item['this_month'], 2) }}</td>
                                                                            <td class="text-right">{{ number_format($item['current_year'], 2) }}</td>
                                                                            <td class="text-right">{{ number_format($item['cumulative'], 2) }}</td>
                                                                        </tr>
                                                                        @endif
                                                                    @endforeach
                                                                    <tr>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <th>{{ __('Goods Purchases:') }}</th>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                    </tr>
                                                                    @foreach($payments as $item)
                                                                        @if(in_array($item['name'], $productPaymentNames))
                                                                        <tr>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <th>{{ $item['name'] }}</th>
                                                                            <td class="text-right">{{ number_format($item['this_month'], 2) }}</td>
                                                                            <td class="text-right">{{ number_format($item['current_year'], 2) }}</td>
                                                                            <td class="text-right">{{ number_format($item['cumulative'], 2) }}</td>
                                                                        </tr>
                                                                        @endif
                                                                    @endforeach

                                                                    <tr class="bg-light">
                                                                        <th>{{ __('Total Receipts') }}</th>
                                                                        <td class="text-right">{{ number_format($totalReceiptsThisMonth, 2) }}</td>
                                                                        <td class="text-right">{{ number_format($totalReceiptsCurrentYear, 2) }}</td>
                                                                        <td class="text-right">{{ number_format($totalReceiptsCumulative, 2) }}</td>
                                                                        <th>{{ __('Total Payments') }}</th>
                                                                        <td class="text-right">{{ number_format($totalPaymentsThisMonth, 2) }}</td>
                                                                        <td class="text-right">{{ number_format($totalPaymentsCurrentYear, 2) }}</td>
                                                                        <td class="text-right">{{ number_format($totalPaymentsCumulative, 2) }}</td>
                                                                    </tr>
                                                                    <tr class="font-bold">
                                                                        <th>{{ __('Closing Balance') }}</th>
                                                                        <td class="text-right">{{ number_format($closingBalance, 2) }}</td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>  -->

                                                            
                                                            <!-- 2nd code  -->

                                                            <!-- <table class="table table-flush" id="report-table">
                                                                <tbody>
                                                                    <tr>
                                                                        <th colspan="8" class="text-center">
                                                                            <h4>{{ __('Store Report') }}</h4>
                                                                            <h5>{{ __('Receipt & Payment Statement') }}</h5>
                                                                        </th>
                                                                    </tr>

                                                                    <tr>
                                                                        <th width="15%">{{ __('Receipt') }}</th>
                                                                        <th width="15%">{{ __('This Month') }}<br>{{ \Carbon\Carbon::parse($filter['startDateRange'])->format('m-d-Y') }}<br>{{ \Carbon\Carbon::parse($filter['endDateRange'])->format('m-d-Y') }}</th>
                                                                        <th width="15%">{{ __('Current Year') }}<br>{{ \Carbon\Carbon::parse($filter['fiscalYearStart'])->format('m-d-Y') }}<br>{{ \Carbon\Carbon::parse($filter['fiscalYearEnd'])->format('m-d-Y') }}</th>
                                                                        <th width="15%">{{ __('Cumulative') }}</th>
                                                                        <th width="15%">{{ __('Payment') }}</th>
                                                                        <th width="15%">{{ __('This Month') }}<br>{{ \Carbon\Carbon::parse($filter['startDateRange'])->format('m-d-Y') }}<br>{{ \Carbon\Carbon::parse($filter['endDateRange'])->format('m-d-Y') }}</th>
                                                                        <th width="15%">{{ __('Current Year') }}<br>{{ \Carbon\Carbon::parse($filter['fiscalYearStart'])->format('m-d-Y') }}<br>{{ \Carbon\Carbon::parse($filter['fiscalYearEnd'])->format('m-d-Y') }}</th>
                                                                        <th width="15%">{{ __('Cumulative') }}</th>
                                                                    </tr>

                                                                    <tr>
                                                                        <th>{{ __('Opening Balance') }}</th>
                                                                        <td class="text-right">{{ number_format($openingBalance, 2) }}</td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                    </tr>

                                                                    @foreach($receipts as $item)
                                                                        @if(!in_array($item['name'], $productReceiptNames))
                                                                        <tr>
                                                                            <th>{{ $item['name'] }}</th>
                                                                            <td class="text-right">{{ number_format($item['this_month'], 2) }}</td>
                                                                            <td class="text-right">{{ number_format($item['current_year'], 2) }}</td>
                                                                            <td class="text-right">{{ number_format($item['cumulative'], 2) }}</td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                        </tr>
                                                                        @endif
                                                                    @endforeach

                                                                    <tr>
                                                                        <td></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th>{{ __('Goods Sales:') }}</th>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                    </tr>

                                                                    @foreach($receipts as $item)
                                                                        @if(in_array($item['name'], $productReceiptNames))
                                                                        <tr>
                                                                            <th>{{ $item['name'] }}</th>
                                                                            <td class="text-right">{{ number_format($item['this_month'], 2) }}</td>
                                                                            <td class="text-right">{{ number_format($item['current_year'], 2) }}</td>
                                                                            <td class="text-right">{{ number_format($item['cumulative'], 2) }}</td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                        </tr>
                                                                        @endif
                                                                    @endforeach

                                                                    @foreach($payments as $item)
                                                                        @if(!in_array($item['name'], $productPaymentNames) && $item['name'] != 'Cash In Hand' && $item['name'] != 'Cash at Bank')
                                                                        <tr>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <th>{{ $item['name'] }}</th>
                                                                            <td class="text-right">{{ number_format($item['this_month'], 2) }}</td>
                                                                            <td class="text-right">{{ number_format($item['current_year'], 2) }}</td>
                                                                            <td class="text-right">{{ number_format($item['cumulative'], 2) }}</td>
                                                                        </tr>
                                                                        @endif
                                                                    @endforeach

                                                                    <tr>
                                                                        <td></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <th>{{ __('Goods Purchases:') }}</th>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                    </tr>

                                                                    @foreach($payments as $item)
                                                                        @if(in_array($item['name'], $productPaymentNames))
                                                                        <tr>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <th>{{ $item['name'] }}</th>
                                                                            <td class="text-right">{{ number_format($item['this_month'], 2) }}</td>
                                                                            <td class="text-right">{{ number_format($item['current_year'], 2) }}</td>
                                                                            <td class="text-right">{{ number_format($item['cumulative'], 2) }}</td>
                                                                        </tr>
                                                                        @endif
                                                                    @endforeach

                                                                    <tr>
                                                                        <td></td>
                                                                    </tr>

                                                                    @foreach($payments as $item)
                                                                        @if($item['name'] == 'Cash In Hand' || $item['name'] == 'Cash at Bank')
                                                                        <tr>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <th>{{ $item['name'] }}</th>
                                                                            <td class="text-right">{{ number_format($item['this_month'], 2) }}</td>
                                                                            <td class="text-right">{{ number_format($item['current_year'], 2) }}</td>
                                                                            <td class="text-right">{{ number_format($item['cumulative'], 2) }}</td>
                                                                        </tr>
                                                                        @endif
                                                                    @endforeach

                                                                    <tr class="bg-light">
                                                                        <th>{{ __('Total Receipts') }}</th>
                                                                        <td class="text-right">{{ number_format($totalReceiptsThisMonth, 2) }}</td>
                                                                        <td class="text-right">{{ number_format($totalReceiptsCurrentYear, 2) }}</td>
                                                                        <td class="text-right">{{ number_format($totalReceiptsCumulative, 2) }}</td>
                                                                        <th>{{ __('Total Payments') }}</th>
                                                                        <td class="text-right">{{ number_format($totalPaymentsThisMonth, 2) }}</td>
                                                                        <td class="text-right">{{ number_format($totalPaymentsCurrentYear, 2) }}</td>
                                                                        <td class="text-right">{{ number_format($totalPaymentsCumulative, 2) }}</td>
                                                                    </tr>

                                                                    <tr class="font-bold">
                                                                        <th>{{ __('Closing Balance') }}</th>
                                                                        <td class="text-right">{{ number_format($closingBalance, 2) }}</td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>  -->



                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <table class="table table-flush" style="margin-bottom: 0;">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th colspan="8" class="text-center">
                                                                                    <h4>{{ __('Store Report') }}</h4>
                                                                                    <h5>{{ __('Receipt & Payment Statement') }}</h5>
                                                                                </th>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <table class="table table-flush" id="report-table">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th width="25%">{{ __('Receipt') }}</th>
                                                                                <th width="25%">{{ __('This Month') }}<br>{{ \Carbon\Carbon::parse($filter['startDateRange'])->format('m-d-Y') }}<br>{{ \Carbon\Carbon::parse($filter['endDateRange'])->format('m-d-Y') }}</th>
                                                                                <th width="25%">{{ __('Current Year') }}<br>{{ \Carbon\Carbon::parse($filter['fiscalYearStart'])->format('m-d-Y') }}<br>{{ \Carbon\Carbon::parse($filter['fiscalYearEnd'])->format('m-d-Y') }}</th>
                                                                                <th width="25%">{{ __('Cumulative') }}</th>
                                                                            </tr>

                                                                            <tr>
                                                                                <th>{{ __('Opening Balance') }}</th>
                                                                                <td class="text-right">{{ number_format($openingBalance, 2) }}</td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>

                                                                            @foreach($receipts as $item)
                                                                                @if(!in_array($item['name'], $productReceiptNames))
                                                                                <tr>
                                                                                    <th>{{ $item['name'] }}</th>
                                                                                    <td class="text-right">{{ number_format($item['this_month'], 2) }}</td>
                                                                                    <td class="text-right">{{ number_format($item['current_year'], 2) }}</td>
                                                                                    <td class="text-right">{{ number_format($item['cumulative'], 2) }}</td>
                                                                                </tr>
                                                                                @endif
                                                                            @endforeach

                                                                            <tr>
                                                                                <td colspan="4"></td>
                                                                            </tr>

                                                                            <tr>
                                                                                <th colspan="4">{{ __('Goods Sales:') }}</th>
                                                                            </tr>

                                                                            @foreach($receipts as $item)
                                                                                @if(in_array($item['name'], $productReceiptNames))
                                                                                <tr>
                                                                                    <th>{{ $item['name'] }}</th>
                                                                                    <td class="text-right">{{ number_format($item['this_month'], 2) }}</td>
                                                                                    <td class="text-right">{{ number_format($item['current_year'], 2) }}</td>
                                                                                    <td class="text-right">{{ number_format($item['cumulative'], 2) }}</td>
                                                                                </tr>
                                                                                @endif
                                                                            @endforeach

                                                                            <tr class="bg-light">
                                                                                <th>{{ __('Total') }}</th>
                                                                                <td class="text-right">{{ number_format($totalReceiptsThisMonth, 2) }}</td>
                                                                                <td class="text-right">{{ number_format($totalReceiptsCurrentYear, 2) }}</td>
                                                                                <td class="text-right">{{ number_format($totalReceiptsCumulative, 2) }}</td>  
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <table class="table table-flush" id="report-table">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th width="25%">{{ __('Payment') }}</th>
                                                                                <th width="25%">{{ __('This Month') }}<br>{{ \Carbon\Carbon::parse($filter['startDateRange'])->format('m-d-Y') }}<br>{{ \Carbon\Carbon::parse($filter['endDateRange'])->format('m-d-Y') }}</th>
                                                                                <th width="25%">{{ __('Current Year') }}<br>{{ \Carbon\Carbon::parse($filter['fiscalYearStart'])->format('m-d-Y') }}<br>{{ \Carbon\Carbon::parse($filter['fiscalYearEnd'])->format('m-d-Y') }}</th>
                                                                                <th width="25%">{{ __('Cumulative') }}</th>
                                                                            </tr>
                                                                            
                                                                            <tr>
                                                                                <td colspan="4"></td>
                                                                            </tr>

                                                                            @foreach($payments as $item)
                                                                                @if(!in_array($item['name'], $productPaymentNames) && $item['name'] != 'Cash In Hand' && $item['name'] != 'Cash at Bank')
                                                                                <tr>
                                                                                    <th>{{ $item['name'] }}</th>
                                                                                    <td class="text-right">{{ number_format($item['this_month'], 2) }}</td>
                                                                                    <td class="text-right">{{ number_format($item['current_year'], 2) }}</td>
                                                                                    <td class="text-right">{{ number_format($item['cumulative'], 2) }}</td>
                                                                                </tr>
                                                                                @endif
                                                                            @endforeach

                                                                            <tr>
                                                                                <td colspan="4"></td>
                                                                            </tr>

                                                                            <tr>
                                                                                <th colspan="4">{{ __('Goods Purchases:') }}</th>
                                                                            </tr>

                                                                            @foreach($payments as $item)
                                                                                @if(in_array($item['name'], $productPaymentNames))
                                                                                <tr>
                                                                                    <th>{{ $item['name'] }}</th>
                                                                                    <td class="text-right">{{ number_format($item['this_month'], 2) }}</td>
                                                                                    <td class="text-right">{{ number_format($item['current_year'], 2) }}</td>
                                                                                    <td class="text-right">{{ number_format($item['cumulative'], 2) }}</td>
                                                                                </tr>
                                                                                @endif
                                                                            @endforeach

                                                                            <tr>
                                                                                <td colspan="4"></td>
                                                                            </tr>

                                                                            @foreach($payments as $item)
                                                                                @if($item['name'] == 'Cash In Hand' || $item['name'] == 'Cash at Bank')
                                                                                <tr>
                                                                                    <th>{{ $item['name'] }}</th>
                                                                                    <td class="text-right">{{ number_format($item['this_month'], 2) }}</td>
                                                                                    <td class="text-right">{{ number_format($item['current_year'], 2) }}</td>
                                                                                    <td class="text-right">{{ number_format($item['cumulative'], 2) }}</td>
                                                                                </tr>
                                                                                @endif
                                                                            @endforeach 

                                                                            <tr class="font-bold">
                                                                                <th>{{ __('Closing Balance') }}</th>
                                                                                <td class="text-right">{{ number_format($closingBalance, 2) }}</td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>

                                                                            <tr class="bg-light">
                                                                                <th>{{ __('Total') }}</th>
                                                                                <td class="text-right">{{ number_format($totalPaymentsThisMonth, 2) }}</td>
                                                                                <td class="text-right">{{ number_format($totalPaymentsCurrentYear, 2) }}</td>
                                                                                <td class="text-right">{{ number_format($totalPaymentsCumulative, 2) }}</td>
                                                                            </tr>

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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>

@endsection