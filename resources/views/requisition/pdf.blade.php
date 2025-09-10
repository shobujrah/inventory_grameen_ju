<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Requisition</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Ensures UTF-8 characters display properly */
            font-size: 14px;
            margin: 0;
            padding: 0;
        }
        .header, .center {
            text-align: center;
        }
        .header img {
            width: 50px;
        }
        .header h2 {
            margin: 0;
        }
        .details {
            margin-top: 10px;
            text-align: center;
        }
        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 10px;
            border: 1px solid #000;
            display: inline-block;
            padding: 5px 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th,
        table td {
            border: 1px solid #000;
            padding: 5px;
        }
        .footer-table td {
            padding-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <!-- Include a logo if needed -->
        <img src="{{ public_path('logo.png') }}" alt="Logo">
        <h2>Grameen Jono Unnayan Sangstha (GJUS)</h2>
        <p><strong>{{ $requisitionheading->branch_name ?? 'Branch Name' }}</strong> Branch</p>
        {{-- <p>Requisition ID: {{ $requisitionheading->id }} | Date: {{ \Carbon\Carbon::parse($requisitionheading->date_from)->format('d-m-Y') }}</p> --}}
    </div>

    <div class="title">Purchase Requisition</div>

    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Item Name</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requisitionlist as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->unit }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer-table" width="100%">
        <tr>
            <td><strong>Accounts Officer</strong></td>
            <td><strong>Auditor/Officer</strong></td>
            <td><strong>Approver</strong></td>
        </tr>
    </table>

</body>
</html>
