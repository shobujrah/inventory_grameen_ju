@php
    //$settings = Utility::settings();
    $settings =" ";
    $color = (!empty($setting['color'])) ? $setting['color'] : 'theme-3';
@endphp


<html lang="en" dir="{{ $settings == 'on' ? 'rtl' : '' }}">

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

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" >
    
    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">

    <title>{{ env('APP_NAME') }} - Trial Balance</title>
    @if (isset($settings['SITE_RTL']) && $settings['SITE_RTL'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
    @endif




    </head>

<script src="{{ asset('js/jquery.min.js') }}"></script>
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
    window.print();
    window.onafterprint = back;

    function back() {
        window.close();
        window.history.back();
    }
</script>

<body class="{{ $color }}">


@php
    $user = \Auth::user();
@endphp

<div class="mt-4">
    <div class="row justify-content-center" id="printableArea">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="account-main-title mb-5">
                        <h5>{{ 'Trial Balance of ' . $user->name . ' as of ' . $filter['startDateRange'] . ' to ' . $filter['endDateRange'] }}
                            </h4>
                    </div>
                    <div
                        class="aacount-title d-flex align-items-center justify-content-between border-top border-bottom py-2">
                        <h6 class="mb-0">{{ __('Account') }}</h6>
                        <h6 class="mb-0 text-center">{{ __('Account Code') }}</h6>
                        <h6 class="mb-0 text-end me-5">{{ __('Debit') }}</h6>
                        <h6 class="mb-0 text-end">{{ __('Credit') }}</h6>

                    </div>
                    @php
                    $totalCredit = 0;
                    $totalDebit = 0;
                    @endphp
                    
                    @foreach ($totalAccounts as $type => $accounts)
                        <div class="account-main-inner border-bottom py-2">
                            <p class="fw-bold ps-2 mb-2">{{ $type }}</p>
                            @foreach ($accounts as $key => $record)
                                <div class="account-inner d-flex align-items-center justify-content-between">
                                    <p class="mb-2"><a
                                            href="{{ route('report.ledger', $record['id']) }}?account={{ $record['id'] }}"
                                            class="text-primary">{{ $record['name'] }}</a>
                                    </p>
                                    <p class="mb-2 text-center">{{ $record['code'] }}</p>
                                    <p class="text-primary mb-2 text-end me-5">
                                        {{ $record['totalDebit'] }}</p>
                                        <p class="text-primary mb-2 float-end text-end">
                                            {{ $record['totalCredit'] }}</p>
                                </div>
                                @php
                                    $totalDebit+= $record['totalDebit'];
                                    $totalCredit+= $record['totalCredit'];
                                @endphp
                            @endforeach
                        </div>
                    @endforeach

                    @if($totalAccounts != [])
                    <div
                        class="aacount-title d-flex align-items-center justify-content-between border-top border-bottom py-2 px-2 pe-0">
                        <h6 class="fw-bold mb-0">{{ 'Total' }}</h6>
                        <h6 class="fw-bold mb-0">{{ ''}}</h6>
                        <h6 class="fw-bold mb-0 text-end me-5">{{ $totalDebit }}</h6>
                        <h6 class="fw-bold mb-0 text-end">{{ $totalCredit }}</h6>

                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>   
</body>

</html>
