<!DOCTYPE html>
<html>
<head>
    <title>Ledger Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid black; padding: 4px; text-align: center; }
        th { background-color: #f2f2f2; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

@if(request('product_id') != '')

    @php
        $name = (request('product_id') == 'all') ? 'All Products' : \App\Models\Product::productName(request('product_id'));
    @endphp

    <h3 style="text-align:center; margin-bottom: 2px;">Ledger Report</h3>
    <p style="text-align:center; margin: 0;">Branch Name: {{ $branches[$selectedBranch] ?? 'N/A' }}</p>
    <p style="text-align:center; margin: 0;">Product Name: {{ $name }}</p>
    <p style="text-align:center; margin: 0;">
        @if(request('month_year'))
            {{ date('F/Y', strtotime(request('month_year'))) }}
        @endif
    </p>

    @php
        $grandTotalUnitPrice = 0;
        $grandTotalQuantity = 0;
        $grandTotalTotalPrice = 0;
        $grandTotalPurchaseQuantity = 0;
        $grandTotalPurchasePrice = 0;
        $grandTotalSalesQuantity = 0;
        $grandTotalSalesPrice = 0;
        $grandTotalStockQuantity = 0;
        $grandTotalStockAmount = 0;
    @endphp

    <table>
        <thead>
            <tr>
                <th>Sl</th>
                <th>Product</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>This month<br>purchase qty</th>
                <th>This month<br>purchase price</th>
                <th>This month<br>expense qty</th>
                <th>This month<br>expense price</th>
                <th>Total Stock qty<br>End of month</th>
                <th>Total Stock Amount<br>End of month</th>
            </tr>
        </thead>
        <tbody>
            @php $sl = 1; @endphp
            @foreach($groupedProducts as $categoryName => $products)
                @if(request('product_id') == 'all' || count($groupedProducts) == 1)
                    <tr>
                        <td colspan="11" style="background-color: #f8f9fa; font-weight: bold;">{{ $categoryName }}</td>
                    </tr>
                @endif

                @php
                    $subtotalUnitPrice = 0;
                    $subtotalQuantity = 0;
                    $subtotalTotalPrice = 0;
                    $subtotalPurchaseQuantity = 0;
                    $subtotalPurchasePrice = 0;
                    $subtotalSalesQuantity = 0;
                    $subtotalSalesPrice = 0;
                    $subtotalStockQuantity = 0;
                    $subtotalStockAmount = 0;
                @endphp

                @foreach($products as $productnamepirce)
                    @php
                        $product = $productDetails[$productnamepirce->product_id] ?? null;
                        $ledgerKey = $productnamepirce->product_id . '-' . $productnamepirce->price;
                        $ledgerEntry = $ledgerData[$ledgerKey] ?? null;
                        $purchaseQuantity = $ledgerEntry ? $ledgerEntry->total_quantity : 0;
                        $finalQuantity = $finalQuantities[$ledgerKey] ?? 0;
                        $salesEntry = $salesData[$ledgerKey] ?? null;
                        $salesQuantity = $salesEntry ? $salesEntry->total_quantity : 0;

                        $subtotalUnitPrice += $productnamepirce->price;
                        $subtotalQuantity += $finalQuantity;
                        $subtotalTotalPrice += $finalQuantity * $productnamepirce->price;
                        $subtotalPurchaseQuantity += $purchaseQuantity;
                        $subtotalPurchasePrice += $purchaseQuantity * $productnamepirce->price;
                        $subtotalSalesQuantity += $salesQuantity;
                        $subtotalSalesPrice += $salesQuantity * $productnamepirce->price;
                        $subtotalStockQuantity += ($finalQuantity + $purchaseQuantity) - $salesQuantity;
                        $subtotalStockAmount += (($finalQuantity * $productnamepirce->price) + ($purchaseQuantity * $productnamepirce->price)) - ($salesQuantity * $productnamepirce->price);

                        $grandTotalUnitPrice += $productnamepirce->price;
                        $grandTotalQuantity += $finalQuantity;
                        $grandTotalTotalPrice += $finalQuantity * $productnamepirce->price;
                        $grandTotalPurchaseQuantity += $purchaseQuantity;
                        $grandTotalPurchasePrice += $purchaseQuantity * $productnamepirce->price;
                        $grandTotalSalesQuantity += $salesQuantity;
                        $grandTotalSalesPrice += $salesQuantity * $productnamepirce->price;
                        $grandTotalStockQuantity += ($finalQuantity + $purchaseQuantity) - $salesQuantity;
                        $grandTotalStockAmount += (($finalQuantity * $productnamepirce->price) + ($purchaseQuantity * $productnamepirce->price)) - ($salesQuantity * $productnamepirce->price);
                    @endphp
                    <tr>
                        <td>{{ $sl++ }}</td>
                        <td>{{ $productDetails[$productnamepirce->product_id]->name ?? 'N/A' }}</td>
                        <td class="text-end">{{ $productnamepirce->price ?? 'N/A' }}</td>
                        <td class="text-center">{{ $finalQuantity }}</td>
                        <td class="text-end">{{ number_format($finalQuantity * $productnamepirce->price, 2) }}</td>
                        <td class="text-center">{{ $purchaseQuantity }}</td>
                        <td class="text-end">{{ number_format($purchaseQuantity * $productnamepirce->price, 2) }}</td>
                        <td class="text-center">{{ $salesQuantity }}</td>
                        <td class="text-end">{{ number_format($salesQuantity * $productnamepirce->price, 2) }}</td>
                        <td class="text-center">{{ ($finalQuantity + $purchaseQuantity) - $salesQuantity }}</td>
                        <td class="text-end">{{ number_format((($finalQuantity * $productnamepirce->price) + ($purchaseQuantity * $productnamepirce->price)) - ($salesQuantity * $productnamepirce->price), 2) }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="2" class="text-end"><strong>Subtotal</strong></td>
                    <td class="text-end"><strong>{{ number_format($subtotalUnitPrice, 2) }}</strong></td>
                    <td class="text-center"><strong>{{ $subtotalQuantity }}</strong></td>
                    <td class="text-end"><strong>{{ number_format($subtotalTotalPrice, 2) }}</strong></td>
                    <td class="text-center"><strong>{{ $subtotalPurchaseQuantity }}</strong></td>
                    <td class="text-end"><strong>{{ number_format($subtotalPurchasePrice, 2) }}</strong></td>
                    <td class="text-center"><strong>{{ $subtotalSalesQuantity }}</strong></td>
                    <td class="text-end"><strong>{{ number_format($subtotalSalesPrice, 2) }}</strong></td>
                    <td class="text-center"><strong>{{ $subtotalStockQuantity }}</strong></td>
                    <td class="text-end"><strong>{{ number_format($subtotalStockAmount, 2) }}</strong></td>
                </tr>

                @php
                    $roundSubtotalUnitPrice = round($subtotalUnitPrice);
                    $roundSubtotalTotalPrice = round($subtotalTotalPrice);
                    $roundSubtotalPurchasePrice = round($subtotalPurchasePrice);
                    $roundSubtotalSalesPrice = round($subtotalSalesPrice);
                    $roundSubtotalStockAmount = round($subtotalStockAmount);
                @endphp
                <tr>
                    <td colspan="2" class="text-end"><strong>Round of Subtotal</strong></td>
                    <td class="text-end"><strong>{{ number_format($roundSubtotalUnitPrice, 2) }}</strong></td>
                    <td class="text-center"><strong>{{ $subtotalQuantity }}</strong></td>
                    <td class="text-end"><strong>{{ number_format($roundSubtotalTotalPrice, 2) }}</strong></td>
                    <td class="text-center"><strong>{{ $subtotalPurchaseQuantity }}</strong></td>
                    <td class="text-end"><strong>{{ number_format($roundSubtotalPurchasePrice, 2) }}</strong></td>
                    <td class="text-center"><strong>{{ $subtotalSalesQuantity }}</strong></td>
                    <td class="text-end"><strong>{{ number_format($roundSubtotalSalesPrice, 2) }}</strong></td>
                    <td class="text-center"><strong>{{ $subtotalStockQuantity }}</strong></td>
                    <td class="text-end"><strong>{{ number_format($roundSubtotalStockAmount, 2) }}</strong></td>
                </tr>
            @endforeach

            <tr>
                <td colspan="2" class="text-end"><strong>Total</strong></td>
                <td class="text-end"><strong>{{ number_format($grandTotalUnitPrice, 2) }}</strong></td>
                <td class="text-center"><strong>{{ $grandTotalQuantity }}</strong></td>
                <td class="text-end"><strong>{{ number_format($grandTotalTotalPrice, 2) }}</strong></td>
                <td class="text-center"><strong>{{ $grandTotalPurchaseQuantity }}</strong></td>
                <td class="text-end"><strong>{{ number_format($grandTotalPurchasePrice, 2) }}</strong></td>
                <td class="text-center"><strong>{{ $grandTotalSalesQuantity }}</strong></td>
                <td class="text-end"><strong>{{ number_format($grandTotalSalesPrice, 2) }}</strong></td>
                <td class="text-center"><strong>{{ $grandTotalStockQuantity }}</strong></td>
                <td class="text-end"><strong>{{ number_format($grandTotalStockAmount, 2) }}</strong></td>
            </tr>

            @php
                $roundGrandTotalUnitPrice = round($grandTotalUnitPrice);
                $roundGrandTotalTotalPrice = round($grandTotalTotalPrice);
                $roundGrandTotalPurchasePrice = round($grandTotalPurchasePrice);
                $roundGrandTotalSalesPrice = round($grandTotalSalesPrice);
                $roundGrandTotalStockAmount = round($grandTotalStockAmount);
            @endphp
            <tr>
                <td colspan="2" class="text-end"><strong>Round of Total</strong></td>
                <td class="text-end"><strong>{{ number_format($roundGrandTotalUnitPrice, 2) }}</strong></td>
                <td class="text-center"><strong>{{ $grandTotalQuantity }}</strong></td>
                <td class="text-end"><strong>{{ number_format($roundGrandTotalTotalPrice, 2) }}</strong></td>
                <td class="text-center"><strong>{{ $grandTotalPurchaseQuantity }}</strong></td>
                <td class="text-end"><strong>{{ number_format($roundGrandTotalPurchasePrice, 2) }}</strong></td>
                <td class="text-center"><strong>{{ $grandTotalSalesQuantity }}</strong></td>
                <td class="text-end"><strong>{{ number_format($roundGrandTotalSalesPrice, 2) }}</strong></td>
                <td class="text-center"><strong>{{ $grandTotalStockQuantity }}</strong></td>
                <td class="text-end"><strong>{{ number_format($roundGrandTotalStockAmount, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

@endif

</body>
</html>
