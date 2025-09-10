
@extends('layouts.master')
@section('content')

<style>
    .barcode {
        height: 2.8cm;
        width: 10.5cm;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        border: 1px dotted #ccc;
        margin: 0.1cm;
        box-sizing: border-box;
        padding: 0;
    }

    .barcode-content {
        width: 5cm;
        height: 2.5cm;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .barcode-content svg {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .barcode-text {
        font-size: 10px;
        margin-top: 2px;
    }

    #print_barcode {
        display: flex;
        flex-wrap: wrap;
        gap: 0.1cm;
    }
</style>

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Product Barcode</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('barcode.list') }}">Back</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {!! Toastr::message() !!}

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body row">
                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">Product Barcode Information</h3>
                                </div>
                                    <div class="row">
                                        <div class="col-12 text-end">
                                            <button id="print-btn" style="margin-top: -34px;" class="btn btn-primary" onclick="generatePDF()">Print Barcode</button>
                                        </div>
                                    </div>
                            </div>
                        </div>

                        </br>

                        <div class="mt-3">
                            <div class="card">
                                <div class="card-header border-0">
                                    <div class="w-100 pt-3" id="print_barcode">
                                        @foreach ($barcodes as $barcode)
                                            <div class="barcode">
                                                <!-- <div class="barcode-content">
                                                    {!! $barcode !!}
                                                </div> -->

                                                <div class="text-center my-3 d-flex justify-content-center" style="margin-top: 15px; width: 100%;">
                                                    {!! DNS1D::getBarcodeHTML($product->sku, 'C128', 1.5, 60) !!}
                                                </div>

                                                <!-- <div class="barcode-text">{{ $product->sku }}</div> -->
                                                <div class="text-center font-weight-bold text-dark">
                                                    {{ $product->sku ?? '' }}
                                                </div>
                                            </div>
                                        @endforeach
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

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        function generatePDF() {
            document.getElementById('print-btn').innerHTML='<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Downloading...';
            var element = document.getElementById('print_barcode');
            var options = {
                margin: 0.3,
                filename: 'Barcode_' + '{{ $product->sku }}' + '.pdf',
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 5,
                    useCORS: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'portrait'
                }
            };
            html2pdf().set(options).from(element).save().then(() => {
                document.getElementById('print-btn').innerHTML = "Print Barcode";
            })
        }
    </script>
@endsection
