@php
    use App\Helpers\NumberToWordsHelper;
@endphp

@extends('layouts.master')
@section('content')

<script type="text/javascript" src="{{ asset('assets/js/plugins/html2pdf.bundle.min.js') }}"></script>

<script>

    function saveAsPDF() {
        var today = new Date();
        var year = today.getFullYear();
        var month = String(today.getMonth() + 1).padStart(2, '0');
        var day = String(today.getDate()).padStart(2, '0');
        var formattedDate = day + "-" + month + "-" + year;

        var filename = "Requisition-" + formattedDate;

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

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Requisition Information</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Requisition</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- message --}}
        {!! Toastr::message() !!}

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">
                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                </div>

                                    @php
                                        $user = auth()->user();
                                    @endphp

                                    @if(!($user->branch_type === 'Branch' && $user->role_name === 'Branch'))
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            @if($requisitionheading->document)
                                                <a href="{{ asset('storage/document/' . $requisitionheading->document) }}" download class="btn btn-secondary">
                                                    <i class="fas fa-file-download"></i> 
                                                </a>
                                            @else
                                                <p>No document available</p>
                                            @endif
                                        </div>
                                    @endif


                                <div class="col-auto text-end float-end ms-auto download-grp"> 
                                    <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()"data-bs-toggle="tooltip" title="{{__('Download')}}" data-original-title="{{__('Download')}}">
                                        <span class="btn-inner--icon"><i class="fas fa-download"></i></span>
                                    </a>
                                </div>

                            </div>
                        </div>

                        <div id="printableArea" class="mt-2">
                            <div class="row mb-3 text-center px-4">
                                <div class="col-sm-12">
                                    <div class="row align-items-center">
                                        <div class="col-sm-2 d-flex justify-content-start">
                                            <img src="{{url('img/logo.png')}}" style="width: 80px; height: 80px;">
                                        </div>
                                        <div class="col-sm-8">
                                            <h5>Grameen Jano Unnayan Sangstha (GJUS)</h5>
                                            <h6>Altazer Rahman Road, Bhola</h6>
                                            <h6>Demand Letter for Purchase of Products/Services</h6>
                                        </div>
                                        <div class="col-sm-2">
                                        </div>
                                    </div>
                                    <br> 
                                    <div class="row mb-3 text-center align-items-center">
                                        <div class="col-sm-4 text-start">
                                            <b>Branch Name:</b> {{ optional($requisitionheading->branch)->name ?? '' }}
                                        </div>
                                        <div class="col-sm-4">
                                            <b>Project Name:</b> {{ $requisitionheading->project->name ?? '' }}
                                        </div>
                                        <div class="col-sm-4 text-end">
                                            <b>Date:</b> {{ $requisitionheading->date_from }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <br> </br>

                            <div class="table-responsive">
                                <table class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                    <thead class="warehouse-thread">
                                        <tr>
                                            <th>Serial</th>
                                            <th>Product Name</th>
                                            <th>Description</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total Price</th>
                                            <th>Comment</th>
                                            <th class="text-center">Status</th>
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
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $requisition->single_product_name ?? '' }}</td>
                                                <td>{{ $requisition->product_description }}</td>
                                                <td>{{ App\Models\Utility::priceFormatEng($requisition->price) }}</td>
                                                <td>{{ $requisition->demand_amount }}</td>
                                                <td>{{ App\Models\Utility::priceFormatEng($requisition->total_price) }}</td>
                                                <td>{{ $requisition->comment }}</td>
                                                <td class="text-center">
                                                    @if ($requisition->headoffice_approval == 0)
                                                        <span class="badge" style="background-color: #28a745; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">
                                                            Approved
                                                        </span>
                                                    @else
                                                        
                                                    @endif
                                                </td>
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
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td ></td>
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
