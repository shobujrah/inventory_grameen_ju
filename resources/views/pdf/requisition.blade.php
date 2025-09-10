

@php
    //$settings = Utility::settings();
    $settings =" ";
    $color = (!empty($setting['color'])) ? $setting['color'] : 'theme-3';
@endphp


<html lang="en" dir="{{$settings == 'on'?'rtl':''}}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/main.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" >

    <title>{{env('APP_NAME')}} - Requisitiont</title>
    @if (isset($settings['SITE_RTL'] ) && $settings['SITE_RTL'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css')}}" id="main-style-link">
    @endif
</head>

<script src="{{ asset('js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

    <script type="text/javascript" src="{{ asset('assets/js/plugins/html2pdf.bundle.min.js') }}"></script>
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
    window.print();
    window.onafterprint = back;

    function back() {
        window.close();
        window.history.back();
    }
</script>

<body class="{{ $color }}">
    <div class="mt-4">
   



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
                                        <!-- <h3 class="page-title">Requisition Information</h3> -->
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="#" id="downloadPdfBtn" class="btn btn-outline-primary me-2"><i class="fas fa-download"></i> </a>  
                                        </a>
                                    </div>
                                </div>
                            </div>

                            
                        <div class="row mb-3 text-center">
                            <div class="col-sm-11">
                                <div class="row align-items-center">
                                    <div class="col-sm-2"> <!-- Adjust the width as needed -->
                                        <img src="{{url('img/logo.png')}}" style="width: 80px; height: 80px">
                                    </div>
                                    <div class="col-sm-10">
                                        <div>Rural People's Development Organization (GJUS)</div>
                                        <div>Altarez Rahman Road, Bhola</div>
                                        <div>Demand Letter for Purchase of Products/Services</div>
                                    </div>
                                </div>
                                <br> <!-- Move the line break outside of the inner row -->
                                <div class="row mb-3 text-center align-items-center">
                                    <div class="col-sm-4 text-left"> 
                                    Branch Name: {{ $requisitionheading->branch_name ?? 'N/A' }}
                                    </div>
                                    <div class="col-sm-4">
                                    Project Name: {{ $requisitionheading->project_name ?? 'N/A' }}
                                    </div>
                                    <div class="col-sm-4 text-right"> 
                                    Date: {{ $requisitionheading->date_from ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>



                        <br> </br>
                        
                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                    <thead class="warehouse-thread">
                                        <tr>
                                            <th>Serial</th>
                                            <th>Product Description</th>
                                            <th>Single Product Name</th>
                                            <th>Price</th>
                                            <th>Demand Amount</th>
                                            <th>Total Price</th>
                                            <th>Stock Level</th>
                                            <th>Purchase Authorization Amount</th>
                                            <th>Comment</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    @foreach ($requisitionlist as $key=>$requisitionlist)
                                        <tr>
                                             <td>{{$key+1}}</td>
                                            <!-- <td>{{$requisitionlist->id}}</td> -->
                                            <td>{{$requisitionlist->product_description}}</td>
                                            <td>{{$requisitionlist->single_product_name}}</td>
                                            <td>{{$requisitionlist->price}}</td>
                                            <td>{{$requisitionlist->demand_amount}}</td>
                                            <td>{{$requisitionlist->total_price}}</td>
                                            <td>{{$requisitionlist->stock_level}}</td>
                                            <td>{{$requisitionlist->purchase_authorization_amount}}</td>
                                            <td>{{$requisitionlist->comment}}</td>
                                            <td>
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