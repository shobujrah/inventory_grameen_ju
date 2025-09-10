@php
    use App\Helpers\NumberToWordsHelper;
@endphp

@extends('layouts.master')

@section('content')

<script type="text/javascript" src="{{ asset('assets/js/plugins/html2pdf.bundle.min.js') }}"></script>

<script>
    function saveAsPDF() {
        var today = new Date();
        var formattedDate = today.toLocaleDateString('en-GB').replace(/\//g, '-');
        var filename = "Pending Requisition-" + formattedDate + ".pdf";

        var element = document.getElementById('printableArea');
        
        var opt = {
            margin: 0.3,
            filename: filename,
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 4, dpi: 72, letterRendering: true },
            jsPDF: { unit: 'in', format: 'A2' }
        };

        html2pdf().set(opt).from(element).save();
    }
</script>

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Pending Requisition View</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Pending Requisition</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {!! Toastr::message() !!}

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">
                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title"></h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()" data-bs-toggle="tooltip" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div id="printableArea" class="mt-2">
                            <div class="row mb-3 text-center px-4">
                                <div class="col-sm-12">
                                    <div class="row align-items-center">
                                        <div class="col-sm-2 d-flex justify-content-start">
                                            <img src="{{ url('img/logo.png') }}" style="width: 80px; height: 80px;">
                                        </div>
                                        <div class="col-sm-8">
                                            <h5>Rural People's Development Organization (GJUS)</h5>
                                            <h6>Altazer Rahman Road, Bhola</h6>
                                            <h6>Demand Letter for Purchase of Products/Services</h6>
                                        </div>
                                        <div class="col-sm-2"></div>
                                    </div>
                                    <br> 
                                    <div class="row mb-3 text-center align-items-center">
                                        <div class="col-sm-4 text-start">
                                            <b>Branch Name:</b> {{ $requisitionheading->branch->name }}
                                        </div>
                                        <div class="col-sm-4">
                                            <b>Project Name:</b> {{ $requisitionheading->project_name }}
                                        </div>
                                        <div class="col-sm-4 text-end">
                                            <b>Date:</b> {{ App\Models\Utility::dateFormat($requisitionheading->date_from) }}
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <br> </br>

                            <div class="table-responsive">
                                <table class="table border-0 table-hover table-center mb-0 datatable table-striped">
                                    <thead class="warehouse-thread">
                                        <tr>
                                            <th>Serial</th>
                                            <th>Product <br>Description</th>
                                            <th>Single <br>Product <br>Name</th>
                                            <th>Price</th>
                                            <th>Demand <br>Amount</th>
                                            <th>Total Price</th>
                                            <th>Stock <br>Level</th>
                                            <th>Purchase <br>Authorization <br>Amount</th>
                                            <th>Comment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalPrice = 0;
                                            $totalStockLevelAmount = 0;
                                            $totalPurchaseAuthorizationAmount = 0;
                                        @endphp

                                        @foreach ($requisitionlist as $key => $requisition)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $requisition->product_description }}</td>
                                                <td>{{ $requisition->single_product_name }}</td>
                                                <td>{{ App\Models\Utility::priceFormatEng($requisition->price) }}</td>
                                                <td>{{ $requisition->demand_amount }}</td>
                                                <td>{{ App\Models\Utility::priceFormatEng($requisition->total_price) }}</td>
                                                <td>{{ $requisition->stock_level }}</td>
                                                <td>{{ App\Models\Utility::priceFormatEng($requisition->purchase_authorization_amount) }}</td>
                                                <td>{{ $requisition->comment }}</td>
                                            </tr>
                                            @php
                                                $totalPrice += $requisition->total_price;
                                                $totalStockLevelAmount += $requisition->stock_level;
                                                $totalPurchaseAuthorizationAmount += $requisition->purchase_authorization_amount;
                                            @endphp
                                        @endforeach
                                        <tr>
                                            <td colspan="4"></td>
                                            <td><b>Total</b></td>
                                            <td><b>{{ App\Models\Utility::priceFormatEng($totalPrice) }}</b></td>
                                            <td><b>{{ $totalStockLevelAmount }}</b></td>
                                            <td><b>{{ App\Models\Utility::priceFormatEng($totalPurchaseAuthorizationAmount) }}</b></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td><b>In words: </b></td>
                                            <td colspan="7"><b style="text-transform: capitalize">{{ App\Helpers\NumberToWordsHelper::convertToWords($totalPrice) }} Taka</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>


                            <br> </br>
                            <br> </br>

                                <div>
                                    <p> <b style="margin-left: 40px;"> Applicant's name and signature: </b> {{$requisitionheading->user->name ?? ''}} </p>
                                    <br>
                                    <br> </br>
                                    <br> </br>
                                    <p style="display: inline-block; margin-left: 40px;">
                                        <b>Purchase committee's name and signature:</b>
                                        <span style="margin-left: 1px;">
                                        <span> <b>1.</b></span>
                                        <span style="margin-left: 150px;"> <b>2.</b></span>
                                        <span style="margin-left: 150px;"> <b>3.</b></span>
                                        <span style="margin-left: 150px;"> <b>4.</b></span>
                                        </span>
                                    </p>
                                    <br>
                                    <br> </br>
                                    <br> </br>
                                    <b> <p style="margin-left: 40px;" >Name and Signature of the approver: </b> </p>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
