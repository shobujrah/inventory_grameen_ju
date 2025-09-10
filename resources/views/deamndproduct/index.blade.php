@extends('layouts.master')
@section('content')

<script type="text/javascript" src="{{ asset('assets/js/plugins/html2pdf.bundle.min.js') }}"></script>

<script>
    function saveAsPDF() {
        const filter = document.querySelector('.dataTables_filter');
        const length = document.querySelector('.dataTables_length');
        const info = document.querySelector('.dataTables_info');
        const paginate = document.querySelector('.dataTables_paginate');

        if (filter) filter.style.display = 'none';
        if (length) length.style.display = 'none';
        if (info) info.style.display = 'none';
        if (paginate) paginate.style.display = 'none';

        var today = new Date();
        var year = today.getFullYear();
        var month = String(today.getMonth() + 1).padStart(2, '0');
        var day = String(today.getDate()).padStart(2, '0');
        var formattedDate = day + "-" + month + "-" + year;

        var filename = "Product Demand Details-" + formattedDate;

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

        html2pdf().set(opt).from(element).save().then(() => {
            if (filter) filter.style.display = '';
            if (length) length.style.display = '';
            if (info) info.style.display = '';
            if (paginate) paginate.style.display = '';
        });
    }
</script>

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Product Demand Details</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('product.demand') }}">Demand List</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Flash message --}}
        {!! Toastr::message() !!}

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">
                        <div class="page-header">
                            <div class="row align-items-center">
                            </div>
                            <div class="col-auto text-end float-end ms-auto download-grp"> 
                                <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()" data-bs-toggle="tooltip" title="{{__('Download')}}" data-original-title="{{__('Download')}}">
                                    <span class="btn-inner--icon"><i class="fas fa-download"></i></span>
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive" id="printableArea">

                            <div class="row mb-3 text-center px-4">
                                <div class="col-sm-12">
                                    <div class="row align-items-center">
                                        <div class="col-sm-2 d-flex justify-content-start">
                                            <img src="{{url('img/logo.png')}}" style="width: 80px; height: 80px;">
                                        </div>
                                        <div class="col-sm-8">
                                            <h5>Grameen Jano Unnayan Sangstha (GJUS)</h5>
                                            <h6>Altazer Rahman Road, Bhola</h6>
                                        </div>
                                        <div class="col-sm-2">
                                        </div>
                                    </div>
                                </div>
                            </div>  

                            <div class="pdf-title" style="text-align: center; margin-bottom: 20px;">
                                <h5>Product Demand Summary</h5>
                            </div>

                            @if($requisitionItems->isEmpty())
                                <div class="alert alert-info">No product demand data found.</div>
                            @else
                                <table class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                    <thead>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Product Name</th>
                                            <th>Demand Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($requisitionItems as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->productnamedemand->name ?? '' }}</td>
                                                <td>{{ $item->total_demand }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
